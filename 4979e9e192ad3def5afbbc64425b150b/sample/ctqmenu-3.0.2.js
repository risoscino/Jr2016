//Menu Module 3.0.2
//Codename: NavSys
//Compatability: FF1+ IE6+ Opr8+

if (typeof domreadycheck=="undefined")
 var domreadycheck=false
var menu={
enableshim: true,
// <v-- Edit Zone --v>
arrowpointers:{
 downarrow: ["/images/darrow.gif", 11,7],
 rightarrow: ["/images/rarrow.gif", 12,12],
 showarrow: {toplevel: true, sublevel: true}
},
hideinterval: 200,
effects: {enableswipe: true, enableslide: true, enablefade: true, duration: 200},
httpsiframesrc: "/blank.html",
// <^-- Edit Zone --^>
topmenuids: [],
topitems: {},
subuls: {},
lastactivesubul: {},
topitemsindex: -1,
ulindex: -1,
hidetimers: {},
shimadded: false,
nonFF: !/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent),
ismobile:navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i) != null,
getoffset:function(what, offsettype){
 return (what.offsetParent)? what[offsettype]+this.getoffset(what.offsetParent, offsettype) : what[offsettype]
},
getoffsetof:function(el){
 el._offsets={left:this.getoffset(el, "offsetLeft"), top:this.getoffset(el, "offsetTop")}
},
getwindowsize:function(){
 this.docwidth=window.innerWidth? window.innerWidth-10 : this.standardbody.clientWidth-10
 this.docheight=window.innerHeight? window.innerHeight-15 : this.standardbody.clientHeight-18
},
gettopitemsdimensions:function(){
 for (var m=0; m<this.topmenuids.length; m++){
  var topmenuid=this.topmenuids[m]
  for (var i=0; i<this.topitems[topmenuid].length; i++){
   var header=this.topitems[topmenuid][i]
   var submenu=document.getElementById(header.getAttribute('rel'))
   header._dimensions={w:header.offsetWidth, h:header.offsetHeight, submenuw:submenu.offsetWidth, submenuh:submenu.offsetHeight}
  }
 }
},
isContained:function(m, e){
 var e=window.event || e
 var c=e.relatedTarget || ((e.type=="mouseover")? e.fromElement : e.toElement)
 while (c && c!=m)try {c=c.parentNode} catch(e){c=m}
 if (c==m)
  return true
 else
  return false
},
addpointer:function(target, imgclass, imginfo, BeforeorAfter){
 var pointer=document.createElement("img")
 pointer.src=imginfo[0]
 pointer.style.width=imginfo[1]+"px"
 pointer.style.height=imginfo[2]+"px"
 if(imgclass=="rightarrowpointer"){
  pointer.style.left=target.offsetWidth-imginfo[2]-2+"px"
 }
 pointer.className=imgclass
 var target_firstEl=target.childNodes[target.firstChild.nodeType!=1? 1 : 0]
 if (target_firstEl && target_firstEl.tagName=="SPAN"){
  target=target_firstEl
 }
 if (BeforeorAfter=="before")
  target.insertBefore(pointer, target.firstChild)
 else
  target.appendChild(pointer)
},
css:function(el, targetclass, action){
 var needle=new RegExp("(^|\\s+)"+targetclass+"($|\\s+)", "ig")
 if (action=="check")
  return needle.test(el.className)
 else if (action=="remove")
  el.className=el.className.replace(needle, "")
 else if (action=="add" && !needle.test(el.className))
  el.className+=" "+targetclass
},
addshimmy:function(target){
 var shim=(!window.opera)? document.createElement("iframe") : document.createElement("div")
 shim.className="iframeshim"
 shim.setAttribute("src", location.protocol=="https:"? this.httpsiframesrc : "about:blank")
 shim.setAttribute("frameborder", "0")
 target.appendChild(shim)
 try{
  shim.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)'
 }
 catch(e){}
 return shim
},
positionshim:function(header, submenu, dir, scrollX, scrollY){
 if (header._istoplevel){
  var scrollY=window.pageYOffset? window.pageYOffset : this.standardbody.scrollTop
  var topgap=header._offsets.top-scrollY
  var bottomgap=scrollY+this.docheight-header._offsets.top-header._dimensions.h
  if (topgap>0){
   this.shimmy.topshim.style.left=scrollX+"px"
   this.shimmy.topshim.style.top=scrollY+"px"
   this.shimmy.topshim.style.width="99%"
   this.shimmy.topshim.style.height=topgap+"px"
  }
  if (bottomgap>0){
   this.shimmy.bottomshim.style.left=scrollX+"px"
   this.shimmy.bottomshim.style.top=header._offsets.top + header._dimensions.h +"px"
   this.shimmy.bottomshim.style.width="99%"
   this.shimmy.bottomshim.style.height=bottomgap+"px"
  }
 }
},
hideshim:function(){
 this.shimmy.topshim.style.width=this.shimmy.bottomshim.style.width=0
 this.shimmy.topshim.style.height=this.shimmy.bottomshim.style.height=0
},
buildmenu:function(mainmenuid, header, submenu, submenupos, istoplevel, dir){
 header._master=mainmenuid
 header._pos=submenupos
 header._istoplevel=istoplevel
 if (istoplevel){
  this.addEvent(header, function(e){
  menu.hidemenu(menu.subuls[this._master][parseInt(this._pos)])
  }, "click")
 }
 this.subuls[mainmenuid][submenupos]=submenu
 header._dimensions={w:header.offsetWidth, h:header.offsetHeight, submenuw:submenu.offsetWidth, submenuh:submenu.offsetHeight}
 this.getoffsetof(header)
 submenu.parentNode.style.left=0
 submenu.parentNode.style.top=0
 submenu.parentNode.style.visibility="hidden"
 submenu.style.visibility="hidden"
 this.addEvent(header, function(e){
  if (menu.ismobile || !menu.isContained(this, e)){
   var submenu=menu.subuls[this._master][parseInt(this._pos)]
   if (this._istoplevel){
    menu.css(this, "selected", "add")
    clearTimeout(menu.hidetimers[this._master][this._pos])
   }
   menu.getoffsetof(header)
   var scrollX=window.pageXOffset? window.pageXOffset : menu.standardbody.scrollLeft
   var scrollY=window.pageYOffset? window.pageYOffset : menu.standardbody.scrollTop
   var submenurightedge=this._offsets.left + this._dimensions.submenuw + (this._istoplevel && dir=="topbar"? 0 : this._dimensions.w)
   var submenubottomedge=this._offsets.top + this._dimensions.submenuh
   var menuleft=(this._istoplevel? this._offsets.left + (dir=="sidebar"? this._dimensions.w : 0) : this._dimensions.w)
   if (submenurightedge-scrollX>menu.docwidth){
    menuleft+= -this._dimensions.submenuw + (this._istoplevel && dir=="topbar" ? this._dimensions.w : -this._dimensions.w)
   }
   submenu.parentNode.style.left=menuleft+"px"
   var menutop=(this._istoplevel? this._offsets.top + (dir=="sidebar"? 0 : this._dimensions.h) : this.offsetTop)
   if (submenubottomedge-scrollY>menu.docheight){
    if (this._dimensions.submenuh<this._offsets.top+(dir=="sidebar"? this._dimensions.h : 0)-scrollY){
     menutop+= - this._dimensions.submenuh + (this._istoplevel && dir=="topbar"? -this._dimensions.h : this._dimensions.h)
    }
    else{
     menutop+= -(this._offsets.top-scrollY) + (this._istoplevel && dir=="topbar"? -this._dimensions.h : 0)
    }
   }
   submenu.parentNode.style.top=menutop+"px"
   if (menu.enableshim && (menu.effects.enableswipe==false || menu.nonFF)){
    menu.positionshim(header, submenu, dir, scrollX, scrollY)
   }
   else{
    submenu.FFscrollInfo={x:scrollX, y:scrollY}
   }
   menu.showmenu(header, submenu, dir)
   if (e.preventDefault)
    e.preventDefault()
   if (e.stopPropagation)
    e.stopPropagation()
  }
 }, (this.ismobile)? "click" : "mouseover")
 this.addEvent(header, function(e){
  var submenu=menu.subuls[this._master][parseInt(this._pos)]
  if (this._istoplevel){
   if (!menu.isContained(this, e) && !menu.isContained(submenu.parentNode, e))
    menu.hidemenu(submenu.parentNode)
  }
  else if (!this._istoplevel && !menu.isContained(this, e)){
   menu.hidemenu(submenu.parentNode)
  }
 }, "mouseout")
},
setopacity:function(el, value){
 el.style.opacity=value
 if (typeof el.style.opacity!="string"){
  el.style.MozOpacity=value
  try{
   if (el.filters){
    el.style.filter="progid:DXImageTransform.Microsoft.alpha(opacity="+ value*100 +")"
   }
  } catch(e){}
 }
},
showmenu:function(header, submenu, dir){
 if (this.effects.enableswipe || this.effects.enablefade){
  if (this.effects.enableswipe){
   var endpoint=(header._istoplevel && dir=="topbar")? header._dimensions.submenuh : header._dimensions.submenuw
   submenu.parentNode.style.width=submenu.parentNode.style.height=0
   submenu.parentNode.style.overflow="hidden"
  }
  if (this.effects.enablefade){
   submenu.parentNode.style.width=submenu.offsetWidth+"px"
   submenu.parentNode.style.height=submenu.offsetHeight+"px"
   this.setopacity(submenu.parentNode, 0)
  }
  submenu._curanimatedegree=0
  submenu.parentNode.style.visibility="visible"
  submenu.style.visibility="visible"
  clearInterval(submenu._animatetimer)
  submenu._starttime=new Date().getTime()
  submenu._animatetimer=setInterval(function(){menu.revealmenu(header, submenu, endpoint, dir)}, 10)
 }
 else{
  submenu.parentNode.style.visibility="visible"
  submenu.style.visibility="visible"
 }
},
revealmenu:function(header, submenu, endpoint, dir){
 var elapsed=new Date().getTime()-submenu._starttime
 if (elapsed<this.effects.duration){
  if (this.effects.enableswipe){
   if (submenu._curanimatedegree==0){
    submenu.parentNode.style[header._istoplevel && dir=="topbar"? "width" : "height"]=(header._istoplevel && dir=="topbar"? submenu.offsetWidth : submenu.offsetHeight)+"px"
   }
   submenu.parentNode.style[header._istoplevel && dir=="topbar"? "height" : "width"]=(submenu._curanimatedegree*endpoint)+"px"
   if (this.effects.enableslide){
    submenu.style[header._istoplevel && dir=="topbar"? "top" : "left"]=Math.floor((submenu._curanimatedegree-1)*endpoint)+"px"
   }
  }
  if (this.effects.enablefade){
   this.setopacity(submenu.parentNode, submenu._curanimatedegree)
  }
 }
 else{
  clearInterval(submenu._animatetimer)
  if (this.effects.enableswipe){
   submenu.parentNode.style.width=submenu.offsetWidth+"px"
   submenu.parentNode.style.height=submenu.offsetHeight+"px"
   submenu.parentNode.style.overflow="visible"
   if (this.effects.enableslide){
    submenu.style.top=0;
    submenu.style.left=0;
   }
  }
  if (this.effects.enablefade){
   this.setopacity(submenu.parentNode, 1)
   submenu.parentNode.style.filter=""
  }
  if (this.enableshim && submenu.FFscrollInfo)
   this.positionshim(header, submenu, dir, submenu.FFscrollInfo.x, submenu.FFscrollInfo.y)
 }
 submenu._curanimatedegree=(1-Math.cos((elapsed/this.effects.duration)*Math.PI)) / 2
},
hidemenu:function(submenu){
 if (typeof submenu._pos!="undefined"){
  this.css(this.topitems[submenu._master][parseInt(submenu._pos)], "selected", "remove")
  if (this.enableshim)
   this.hideshim()
 }
 clearInterval(submenu.firstChild._animatetimer)
 submenu.style.left=0
 submenu.style.top="-1000px"
 submenu.style.visibility="hidden"
 submenu.firstChild.style.visibility="hidden"
},
addEvent:function(target, functionref, tasktype) {
 if (target.addEventListener)
  target.addEventListener(tasktype, functionref, false);
 else if (target.attachEvent)
  target.attachEvent('on'+tasktype, function(){return functionref.call(target, window.event)});
},
domready:function(functionref){
 if (domreadycheck){
  functionref()
  return
 }
 if (document.addEventListener) {
  document.addEventListener("DOMContentLoaded", function(){
   document.removeEventListener("DOMContentLoaded", arguments.callee, false )
   functionref();
   domreadycheck=true
  }, false )
 }
 else if (document.attachEvent){
  if ( document.documentElement.doScroll && window == window.top) (function(){
   if (domreadycheck){
    functionref()
    return
   }
   try{
    document.documentElement.doScroll("left")
   }catch(error){
    setTimeout( arguments.callee, 0)
    return;
   }
   functionref();
   domreadycheck=true
  })();
 }
 if (document.attachEvent && parent.length>0)
  this.addEvent(window, function(){functionref()}, "load");
},
init:function(mainmenuid, dir){
 this.standardbody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body
 this.topitemsindex=-1
 this.ulindex=-1
 this.topmenuids.push(mainmenuid)
 this.topitems[mainmenuid]=[]
 this.subuls[mainmenuid]=[]
 this.hidetimers[mainmenuid]=[]
 if (this.enableshim && !this.shimadded){
  this.shimmy={}
  this.shimmy.topshim=this.addshimmy(document.body)
  this.shimmy.bottomshim=this.addshimmy(document.body)
  this.shimadded=true
 }
 var menubar=document.getElementById(mainmenuid)
 var alllinks=menubar.getElementsByTagName("a")
 this.getwindowsize()
 for (var i=0; i<alllinks.length; i++){
  if (alllinks[i].getAttribute('rel')){
   this.topitemsindex++
   this.ulindex++
   var menuitem=alllinks[i]
   this.topitems[mainmenuid][this.topitemsindex]=menuitem
   var dropul=document.getElementById(menuitem.getAttribute('rel'))
   var shelldiv=document.createElement("div")
   shelldiv.className="submenustyle"
   dropul.removeAttribute("class")
   shelldiv.appendChild(dropul)
   document.body.appendChild(shelldiv)
   shelldiv.style.zIndex=2000
   shelldiv._master=mainmenuid
   shelldiv._pos=this.topitemsindex
   this.addEvent(shelldiv, function(){menu.hidemenu(this)}, "click")
   var arrowclass=(dir=="sidebar")? "rightarrowpointer" : "downarrowpointer"
   var arrowpointer=(dir=="sidebar")? this.arrowpointers.rightarrow : this.arrowpointers.downarrow
   if (this.arrowpointers.showarrow.toplevel)
    this.addpointer(menuitem, arrowclass, arrowpointer, (dir=="sidebar")? "before" : "after")
   this.buildmenu(mainmenuid, menuitem, dropul, this.ulindex, true, dir)
   shelldiv.onmouseover=function(){
    clearTimeout(menu.hidetimers[this._master][this._pos])
   }
   this.addEvent(shelldiv, function(e){
    if (!menu.isContained(this, e) && !menu.isContained(menu.topitems[this._master][parseInt(this._pos)], e)){
     var dropul=this
     if (menu.enableshim)
      menu.hideshim()
     menu.hidetimers[this._master][this._pos]=setTimeout(function(){
      menu.hidemenu(dropul)
     }, menu.hideinterval)
    }
   }, "mouseout")
   var subuls=dropul.getElementsByTagName("ul")
   for (var c=0; c<subuls.length; c++){
    this.ulindex++
    var parentli=subuls[c].parentNode
    var subshell=document.createElement("div")
    subshell.appendChild(subuls[c])
    parentli.appendChild(subshell)
    if (this.arrowpointers.showarrow.sublevel)
     this.addpointer(parentli.getElementsByTagName("a")[0], "rightarrowpointer", this.arrowpointers.rightarrow, "before")
    this.buildmenu(mainmenuid, parentli, subuls[c], this.ulindex, false, dir)
   }
  }
 }
 this.addEvent(window, function(){menu.getwindowsize(); menu.gettopitemsdimensions()}, "resize")
},
setup:function(mainmenuid, dir){
 this.domready(function(){menu.init(mainmenuid, dir)})
}
}