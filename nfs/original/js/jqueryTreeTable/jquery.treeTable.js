/* jQuery treeTable Plugin 2.2.3 - http://ludo.cubicphuse.nl/jquery-plugins/treeTable/ */
(function(j$) {
  // Helps to make options available to all functions
  // TODO: This gives problems when there are both expandable and non-expandable
  // trees on a page. The options shouldn't be global to all these instances!
  var options;
  var defaultPaddingLeft;
  
  j$.fn.treeTable = function(opts) {
    options = j$.extend({}, j$.fn.treeTable.defaults, opts);
    
    return this.each(function() {
      j$(this).addClass("treeTable").find("tbody tr").each(function() {
        // Initialize root nodes only if possible
        if(!options.expandable || j$(this)[0].className.search("child-of-") == -1) {
          // To optimize performance of indentation, I retrieve the padding-left
          // value of the first root node. This way I only have to call +css+ 
          // once.
          if (isNaN(defaultPaddingLeft)) {
            defaultPaddingLeft = parseInt(j$(j$(this).children("td")[options.treeColumn]).css('padding-left'), 10);
          }
          
          initialize(j$(this));
        } else if(options.initialState == "collapsed") {
          this.style.display = "none"; // Performance! j$(this).hide() is slow...
        }
      });
    });
  };
  
  j$.fn.treeTable.defaults = {
    childPrefix: "child-of-",
    clickableNodeNames: false,
    expandable: true,
    indent: 19,
    initialState: "collapsed",
    treeColumn: 0
  };
  
  // Recursively hide all node's children in a tree
  j$.fn.collapse = function() {
    j$(this).addClass("collapsed");
    
    childrenOf(j$(this)).each(function() {
      if(!j$(this).hasClass("collapsed")) {
        j$(this).collapse();
      }
      
      this.style.display = "none"; // Performance! j$(this).hide() is slow...
    });
    
    return this;
  };
  
  // Recursively show all node's children in a tree
  j$.fn.expand = function() {
    j$(this).removeClass("collapsed").addClass("expanded");
    
    childrenOf(j$(this)).each(function() {
      initialize(j$(this));
      
      if(j$(this).is(".expanded.parent")) {
        j$(this).expand();
      }
      
      // this.style.display = "table-row"; // Unfortunately this is not possible with IE :-(
      j$(this).show();
    });
    
    return this;
  };
  
  // Add an entire branch to +destination+
  j$.fn.appendBranchTo = function(destination) {
    var node = j$(this);
    var parent = parentOf(node);
    
    var ancestorNames = j$.map(ancestorsOf(j$(destination)), function(a) { return a.id; });
    
    // Conditions:
    // 1: +node+ should not be inserted in a location in a branch if this would
    //    result in +node+ being an ancestor of itself.
    // 2: +node+ should not have a parent OR the destination should not be the
    //    same as +node+'s current parent (this last condition prevents +node+
    //    from being moved to the same location where it already is).
    // 3: +node+ should not be inserted as a child of +node+ itself.
    if(j$.inArray(node[0].id, ancestorNames) == -1 && (!parent || (destination.id != parent[0].id)) && destination.id != node[0].id) {
      indent(node, ancestorsOf(node).length * options.indent * -1); // Remove indentation
      
      if(parent) { node.removeClass(options.childPrefix + parent[0].id); }
      
      node.addClass(options.childPrefix + destination.id);
      move(node, destination); // Recursively move nodes to new location
      indent(node, ancestorsOf(node).length * options.indent);
    }
    
    return this;
  };
  
  // Add reverse() function from JS Arrays
  j$.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
  };
  
  // Toggle an entire branch
  j$.fn.toggleBranch = function() {
    if(j$(this).hasClass("collapsed")) {
      j$(this).expand();
    } else {
      j$(this).removeClass("expanded").collapse();
    }
    
    return this;
  };
  
  // === Private functions
  
  function ancestorsOf(node) {
    var ancestors = [];
    while(node = parentOf(node)) {
      ancestors[ancestors.length] = node[0];
    }
    return ancestors;
  };
  
  function childrenOf(node) {
    return j$("table.treeTable tbody tr." + options.childPrefix + node[0].id);
  };
  
  function getPaddingLeft(node) {
    var paddingLeft = parseInt(node[0].style.paddingLeft, 10);
    return (isNaN(paddingLeft)) ? defaultPaddingLeft : paddingLeft;
  }
  
  function indent(node, value) {
    var cell = j$(node.children("td")[options.treeColumn]);
    cell[0].style.paddingLeft = getPaddingLeft(cell) + value + "px";
    
    childrenOf(node).each(function() {
      indent(j$(this), value);
    });
  };
  
  function initialize(node) {
    if(!node.hasClass("initialized")) {
      node.addClass("initialized");
      
      var childNodes = childrenOf(node);
      
      if(!node.hasClass("parent") && childNodes.length > 0) {
        node.addClass("parent");
      }
      
      if(node.hasClass("parent")) {
        var cell = j$(node.children("td")[options.treeColumn]);
        var padding = getPaddingLeft(cell) + options.indent;
        
        childNodes.each(function() {
          j$(this).children("td")[options.treeColumn].style.paddingLeft = padding + "px";
        });
        
        if(options.expandable) {
          cell.prepend('<span style="margin-left: -' + options.indent + 'px; padding-left: ' + options.indent + 'px" class="expander"></span>');
          j$(cell[0].firstChild).click(function() { node.toggleBranch(); });
          
          if(options.clickableNodeNames) {
            cell[0].style.cursor = "pointer";
            j$(cell).click(function(e) {
              // Don't double-toggle if the click is on the existing expander icon
              if (e.target.className != 'expander') {
                node.toggleBranch();
              }
            });
          }
          
          // Check for a class set explicitly by the user, otherwise set the default class
          if(!(node.hasClass("expanded") || node.hasClass("collapsed"))) {
            node.addClass(options.initialState);
          }

          if(node.hasClass("expanded")) {
            node.expand();
          }
        }
      }
    }
  };
  
  function move(node, destination) {
    node.insertAfter(destination);
    childrenOf(node).reverse().each(function() { move(j$(this), node[0]); });
  };
  
  function parentOf(node) {
    var classNames = node[0].className.split(' ');
    for(key in classNames) {
      if(typeof(classNames[key]) == "string" && classNames[key].match("child-of-")) {
        return j$("#" + classNames[key].substring(9));
      }
    }
    
  };
})(jQuery);