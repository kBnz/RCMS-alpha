
exts = [
  {id:0,  name:'text'},
  {id:13, name:'autocomplete'},
  {id:1,  name:'text_area'},
  {id:6,  name:'wysiwyg'},
  {id:2,  name:'select_box'},
  {id:5,  name:'multiple_choice_checkbox', label:'/label/multiple_choice_checkbox'},
  {id:4,  name:'image', label:'/label/image'},
  {id:7,  name:'link'},
  {id:8,  name:'date'},
  {id:12, name:'tdfk'},
  {id:9,  name:'file'},
  {id:10, name:'table'},
  {id:11, name:'location'},
  {id:20, name:'relation_selectbox', label:'/label/relation_selectbox'},
]

exts.each_with_index do |ext, i|
  filename = "extensions/%d.html" % [ext[:id]]
  pg = ''

  File.open(filename, 'w') do |f|
    f.write(pg)
  end
  #no (i + 1).to_s.rjust(2, '0')
  #puts ext[:label] if ext[:label]
end
