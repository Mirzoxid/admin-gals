(function(){function t(a,b,c,d){function e(){if(f)return null;var h=b;b.childNodes&&b.childNodes.length&&!l?b=b[d?"lastChild":"firstChild"]:b[d?"previousSibling":"nextSibling"]?(b=b[d?"previousSibling":"nextSibling"],l=!1):b.parentNode&&(b=b.parentNode,b===a&&(f=!0),l=!0,e());h===c&&(f=!0);return h}d=!!d;b=b||a[d?"lastChild":"firstChild"];var f=!b,l=!1;return e}function v(a){for(var b=1;b<arguments.length;b++)for(var c in arguments[b])a[c]=arguments[b][c];return a}function w(a){return(a||"").replace(/^\s+|\s+$/g,
"")}function I(a,b){var c="";document.defaultView&&document.defaultView.getComputedStyle?c=document.defaultView.getComputedStyle(a,"").getPropertyValue(b):a.currentStyle&&(b=b.replace(/\-(\w)/g,function(a,b){return b.toUpperCase()}),c=a.currentStyle[b]);return c}function y(a,b){for(;a&&!k(a,b);)a=a.parentNode;return a||null}function A(a,b){for(var c=t(a),d=null;d=c();)if(1===d.nodeType&&k(d,b))return d;return null}function B(a){a=t(a);for(var b=null;(b=a())&&3!==b.nodeType;);return b}function q(a,
b){if(a.getElementsByClassName)return a.getElementsByClassName(b);for(var c=[],d,e=t(a);d=e();)1==d.nodeType&&k(d,b)&&c.push(d);return c}function x(a){for(var b=[],c=t(a);a=c();)3===a.nodeType&&b.push(a);return b}function C(a){return new RegExp("(^|\\s+)"+a+"(?:$|\\s+)","g")}function k(a,b){return C(b).test(a.className)}function z(a,b){C(b).test(a.className)||(a.className=a.className+" "+b)}function u(a,b){var c=C(b);c.test(a.className)&&(a.className=w(a.className.replace(c,"$1")))}function G(a,b){for(var c=
0,d=b.length;c<d;c++)if(b[c]===a)return c;return-1}function m(a,b,c){a.addEventListener?a.addEventListener(b,c,!1):a.attachEvent&&a.attachEvent("on"+b,c)}function r(a,b,c){a.removeEventListener?a.removeEventListener(b,c,!1):a.detachEvent&&a.detachEvent("on"+b,c)}function D(a){a.preventDefault?a.preventDefault():a.returnValue=!1}function J(a){if(null==a.pageX){var b=document.documentElement,c=document.body;return{x:a.clientX+(b&&b.scrollLeft||c&&c.scrollLeft||0)-(b.clientLeft||0),y:a.clientY+(b&&b.scrollTop||
c&&c.scrollTop||0)-(b.clientTop||0)}}return{x:a.pageX,y:a.pageY}}var g=function(){};g.prototype={setHash:function(a){window.location.hash=a},getHash:function(){return window.location.hash},addHashchange:function(a){this.callback=a;m(window,"hashchange",a)},destroy:function(){this.callback&&r(window,"hashchange",this.callback)},_removeHash:function(){window.history.pushState?history.pushState("",document.title,window.location.pathname+window.location.search):this.setHash("")}};var n=function(a){a=
a||{};"select_message"in a&&(a.selectMessage=a.select_message);"enable_haschange"in a&&(a.enableHaschange=a.enable_haschange);"is_block"in a&&(a.isBlock=a.is_block);this.options=v({},n.defaultOptions,a);v(this,{counter:0,ranges:{},blocks:{}});this.init()};n.version="25.04.2013-09:55:11";n.LocationHandler=g;n.defaultOptions={regexp:"[^\\s,;:\u2013.!?<>\u2026\\n\u00a0\\*]+",selectable:"selectable-content",marker:"txtselect_marker",ignored:null,selectMessage:null,location:new g,validate:!1,enableHaschange:!0,
onMark:null,onUnmark:null,onHashRead:function(){var a=A(this.selectable,"user_selection_true");a&&!this.hashWasRead&&(this.hashWasRead=!0,window.setTimeout(function(){for(var b=0,c=0;a;)b+=a.offsetLeft,c+=a.offsetTop,a=a.offsetParent;window.scrollTo(b,c-150)},1))},isBlock:function(a){return"BR"==a.nodeName||-1==G(I(a,"display"),["inline","none"])}};n.prototype={init:function(){this.selectable="string"==typeof this.options.selectable?document.getElementById(this.options.selectable):this.options.selectable;
"string"==typeof this.options.marker?(this.marker=document.getElementById(this.options.marker),null===this.marker&&(this.marker=document.createElement("a"),this.marker.setAttribute("id",this.options.marker),this.marker.setAttribute("href","#"),document.body.appendChild(this.marker))):this.marker=this.options.marker;if("string"!=typeof this.options.regexp)throw"regexp is set as string";this.regexp=new RegExp(this.options.regexp,"ig");this.selectable&&(this.isIgnored=this.constructIgnored(this.options.ignored),
this.options.selectMessage&&this.initMessage(),this.enumerateElements(),"ontouchstart"in window||window.DocumentTouch&&document instanceof DocumentTouch?(this.touchEnd=p(this.touchEnd,this),m(this.selectable,"touchend",this.touchEnd)):(this.mouseUp=p(this.mouseUp,this),m(this.selectable,"mouseup",this.mouseUp)),this.markerClick=p(this.markerClick,this),m(this.marker,"click",this.markerClick),m(this.marker,"touchend",this.markerClick),this.hideMarker=p(this.hideMarker,this),m(document,"click",this.hideMarker),
this.options.enableHaschange&&(this.hashChange=p(this.hashChange,this),this.options.location.addHashchange(this.hashChange)),this.readHash())},destroy:function(){u(this.marker,"show");this.options.selectMessage&&this.hideMessage();r(this.selectable,"mouseup",this.mouseUp);r(this.selectable,"touchEnd",this.touchEnd);r(this.marker,"click",this.markerClick);r(this.marker,"touchend",this.markerClick);r(document,"click",this.hideMarker);this.options.location.destroy();var a=q(this.selectable,"user_selection_true");
this.removeTextSelection(a);for(var b=q(this.selectable,"closewrap"),a=b.length;a--;)b[a].parentNode.removeChild(b[a]);b=q(this.selectable,"masha_index");for(a=b.length;a--;)b[a].parentNode.removeChild(b[a])},mouseUp:function(a){var b=J(a);window.setTimeout(p(function(){this.showMarker(b)},this),1)},touchEnd:function(){window.setTimeout(p(function(){var a=window.getSelection();if(a.rangeCount&&(a=a.getRangeAt(0).getClientRects(),a.length)){var a=a[a.length-1],b=document.body;this.showMarker({x:a.left+
a.width+b.scrollLeft,y:a.top+a.height/2+b.scrollTop})}},this),1)},hashChange:function(){if(this.lastHash!=this.options.location.getHash()){var a=[],b;for(b in this.ranges)a.push(b);this.deleteSelections(a);this.readHash()}},hideMarker:function(a){(a.target||a.srcElement)!=this.marker&&u(this.marker,"show");this.lastRange=null},markerClick:function(a){D(a);a.stopPropagation?a.stopPropagation():a.cancelBubble=!0;a=a.target||a.srcElement;if(!k(this.marker,"masha-marker-bar")||k(a,"masha-social")||k(a,
"masha-marker"))if(u(this.marker,"show"),this.rangeIsSelectable()&&(this.addSelection(),this.updateHash(),this.options.onMark&&this.options.onMark.call(this),this.options.selectMessage&&this._showMessage(),k(a,"masha-social")&&(a=a.getAttribute("data-pattern"))))a=a.replace("{url}",encodeURIComponent(window.location.toString())),this.openShareWindow(a)},openShareWindow:function(a){window.open(a,"","status=no,toolbar=no,menubar=no,width=800,height=400")},getMarkerCoords:function(a,b){return{x:b.x+
5,y:b.y-33,width:b.width}},getPositionChecksum:function(a){for(var b="",c=0;3>c;c++){var d=(a()||"").charAt(0);d&&(d=d.charCodeAt(0)%62,d="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890".charAt(d));b+=d}return b},showMarker:function(a){var b=new RegExp(this.options.regexp,"g"),c=window.getSelection().toString();""!=c&&b.test(c)&&this.rangeIsSelectable()&&(a=this.getMarkerCoords(this.marker,a),this.marker.style.top=a.y+"px",this.marker.style.left=a.x+"px",a=window.getSelection(),a.rangeCount&&
(this.lastRange=a.getRangeAt(0)),z(this.marker,"show"))},deleteSelections:function(a){for(var b=a.length;b--;){var c=a[b],d=q(this.selectable,c),e=A(d[d.length-1],"closewrap");e.parentNode.removeChild(e);this.removeTextSelection(d);delete this.ranges[c]}},removeTextSelection:function(a){for(var b=a.length;b--;){for(var c=a[b],d=0;d<c.childNodes.length;d++)c.parentNode.insertBefore(c.childNodes[d],c);c.parentNode.removeChild(c)}},isInternal:function(a){for(;a.parentNode;){if(a==this.selectable)return!0;
a=a.parentNode}return!1},_siblingNode:function(a,b,c,d,e){for(e=e||this.regexp;a.parentNode&&this.isInternal(a);){for(;a[b+"Sibling"];){for(a=a[b+"Sibling"];1==a.nodeType&&a.childNodes.length;)a=a[c+"Child"];if(3==a.nodeType&&null!=a.data.match(e))return{_container:a,_offset:d*a.data.length}}a=a.parentNode}return null},prevNode:function(a,b){return this._siblingNode(a,"previous","last",1,b)},nextNode:function(a,b){return this._siblingNode(a,"next","first",0,b)},wordCount:function(a){var b=0;if(3==
a.nodeType)(a=a.nodeValue.match(this.regexp))&&(b+=a.length);else if(a.childNodes&&a.childNodes.length){a=x(a);for(var c=a.length;c--;)b+=a[c].nodeValue.match(this.regexp).length}return b},words:function(a,b,c){1==a.nodeType&&(a=B(a));b=a.data.substring(0,b).match(this.regexp);null!=b?("start"==c&&(b=b.length+1),"end"==c&&(b=b.length)):b=1;c=a;a=this.getNum(a);for(var d=this.getFirstTextNode(a);c&&c!=d;)c=this.prevNode(c,/.*/)._container,b+=this.wordCount(c);return a+":"+b},symbols:function(a){var b=
0;if(3==a.nodeType)b=a.nodeValue.length;else if(a.childNodes&&a.childNodes.length){a=x(a);for(var c=a.length;c--;)b+=a[c].nodeValue.length}return b},updateHash:function(){var a=[],b;for(b in this.ranges)a.push(this.ranges[b]);a.length?(this.lastHash=a="#sel="+a.join(";"),this.options.location.setHash(a)):this.options.location._removeHash()},readHash:function(){var a=this.splittedHash();if(a){for(var b=0;b<a.length;b++)this.deserializeSelection(a[b]);this.updateHash();this.options.onHashRead&&this.options.onHashRead.call(this)}},
splittedHash:function(){var a=this.options.location.getHash();if(!a)return null;a=a.replace(/^#/,"").replace(/;+$/,"");if(!/^sel\=(?:\d+\:\d+(?:\:[^:;]*)?\,|%2C\d+\:\d+(?:\:[^:;]*)?;)*\d+\:\d+(?:\:[^:;]*)?\,|%2C\d+\:\d+(?:\:[^:;]*)?$/.test(a))return null;a=a.substring(4,a.length);return a.split(";")},deserializeSelection:function(a){var b=window.getSelection();0<b.rangeCount&&b.removeAllRanges();(a=this.deserializeRange(a))&&this.addSelection(a)},deserializeRange:function(a){var b=/^([0-9A-Za-z:]+)(?:,|%2C)([0-9A-Za-z:]+)$/.exec(a),
c=b[1].split(":"),b=b[2].split(":");if(parseInt(c[0],10)<parseInt(b[0],10)||c[0]==b[0]&&parseInt(c[1],10)<=parseInt(b[1],10)){var d=this.deserializePosition(c,"start"),e=this.deserializePosition(b,"end");if(d.node&&e.node){var f=document.createRange();f.setStart(d.node,d.offset);f.setEnd(e.node,e.offset);if(!this.options.validate||this.validateRange(f,c[2],b[2]))return f}}window.console&&"function"==typeof window.console.warn&&window.console.warn("Cannot deserialize range: "+a);return null},validateRange:function(a,
b,c){var d=!0,e;b&&(e=this.getPositionChecksum(a.getWordIterator(this.regexp)),d=d&&b==e);c&&(e=this.getPositionChecksum(a.getWordIterator(this.regexp,!0)),d=d&&c==e);return d},getRangeChecksum:function(a){return[this.getPositionChecksum(a.getWordIterator(this.regexp)),this.getPositionChecksum(a.getWordIterator(this.regexp,!0))]},deserializePosition:function(a,b){for(var c=this.blocks[parseInt(a[0],10)],d,e=0;c;){for(var f=new RegExp(this.options.regexp,"ig"),l;null!=(l=f.exec(c.data));)if(e++,e==
a[1])return"start"==b&&(d=l.index),"end"==b&&(d=f.lastIndex),{node:c,offset:parseInt(d,10)};(c=(c=this.nextNode(c,/.*/))?c._container:null)&&this.isFirstTextNode(c)&&(c=null)}return{node:null,offset:0}},serializeRange:function(a){var b=this.words(a.startContainer,a.startOffset,"start"),c=this.words(a.endContainer,a.endOffset,"end");this.options.validate&&(a=this.getRangeChecksum(a),b+=":"+a[0],c+=":"+a[1]);return b+","+c},checkSelection:function(a){this.checkPosition(a,a.startOffset,a.startContainer,
"start");this.checkPosition(a,a.endOffset,a.endContainer,"end");this.checkBrackets(a);this.checkSentence(a);return a},checkPosition:function(a,b,c,d){function e(a){return null!=a.match(g.regexp)}function f(a){return null==a.match(g.regexp)}function l(a,b,c){for(;0<b&&c(a.data.charAt(b-1));)b--;return b}function h(a,b,c){for(;b<a.data.length&&c(a.data.charAt(b));)b++;return b}var g=this;if(1==c.nodeType&&0<b)if(b<c.childNodes.length)c=c.childNodes[b],b=0;else{var k=x(c);k.length&&(c=k[k.length-1],
b=c.data.length)}if("start"==d){1==c.nodeType&&""!=w(c.textContent||c.innerText)&&(c=B(c),b=0);if(3!=c.nodeType||null==c.data.substring(b).match(this.regexp))b=this.nextNode(c),c=b._container,b=b._offset;b=h(c,b,f);b=l(c,b,e);a.setStart(c,b)}if("end"==d){1==c.nodeType&&""!=w(c.textContent||c.innerText)&&0!=b&&(c=c.childNodes[a.endOffset-1],k=x(c),c=k[k.length-1],b=c.data.length);if(3!=c.nodeType||null==c.data.substring(0,b).match(this.regexp))b=this.prevNode(c),c=b._container,b=b._offset;b=l(c,b,
f);b=h(c,b,e);a.setEnd(c,b)}},checkBrackets:function(a){this._checkBrackets(a,"(",")",/\(|\)/g,/\(x*\)/g);this._checkBrackets(a,"\u00ab","\u00bb",/\\u00ab|\\u00bb/g,/\u00abx*\u00bb/g)},_checkBrackets:function(a,b,c,d,e){var f=a.toString();if(d=f.match(d)){d=d.join("");for(var l=d.length+1;d.length<l;)l=d.length,d=d.replace(e,"x");d.charAt(d.length-1)==c&&f.charAt(f.length-1)==c&&(1==a.endOffset?(c=this.prevNode(a.endContainer),a.setEnd(c.container,c.offset)):a.setEnd(a.endContainer,a.endOffset-1));
d.charAt(0)==b&&f.charAt(0)==b&&(a.startOffset==a.startContainer.data.length?(c=this.nextNode(a.endContainer),a.setStart(c.container,c.offset)):a.setStart(a.startContainer,a.startOffset+1))}},checkSentence:function(a){function b(){a.setEnd(c._container,c._offset+1)}var c,d;if(a.endOffset==a.endContainer.data.length){c=this.nextNode(a.endContainer,/.*/);if(!c)return null;d=c._container.data.charAt(0)}else c={_container:a.endContainer,_offset:a.endOffset},d=a.endContainer.data.charAt(a.endOffset);if(d.match(/\.|\?|\!/)){d=
a.toString();if(d.match(/(\.|\?|\!)\s+[A-Z\u0410-\u042f\u0401]/)||0==a.startOffset&&a.startContainer.previousSibling&&1==a.startContainer.previousSibling.nodeType&&k(a.startContainer.previousSibling,"masha_index"))return b();for(var e,f=a.getElementIterator();e=f();)if(1==e.nodeType&&k(e,"masha_index"))return b();return d.charAt(0).match(/[A-Z\u0410-\u042f\u0401]/)&&(d=a.startContainer.data.substring(0,a.startOffset),d.match(/\S/)||(d=this.prevNode(a.startContainer,/\W*/)._container.data),d=w(d),
d.charAt(d.length-1).match(/(\.|\?|\!)/))?b():null}},mergeSelections:function(a){var b=[],c=a.getElementIterator(),d=c(),e=d,f=y(d,"user_selection_true");f&&(f=/(num\d+)(?:$| )/.exec(f.className)[1],a.setStart(B(A(this.selectable,f)),0),b.push(f));for(;d;)1==d.nodeType&&k(d,"user_selection_true")&&(e=/(num\d+)(?:$|)/.exec(d.className)[0],-1==G(e,b)&&b.push(e)),e=d,d=c();if(e=y(e,"user_selection_true"))e=/(num\d+)(?:$| )/.exec(e.className)[1],c=(c=q(this.selectable,e))?c[c.length-1]:null,c=x(c),c=
c[c.length-1],a.setEnd(c,c.length);b.length&&(c=a.startContainer,d=a.startOffset,e=a.endContainer,f=a.endOffset,this.deleteSelections(b),a.setStart(c,d),a.setEnd(e,f));return a},addSelection:function(a){a=a||this.getFirstRange();a=this.checkSelection(a);a=this.mergeSelections(a);var b="num"+this.counter;this.ranges[b]=this.serializeRange(a);a.wrapSelection(b+" user_selection_true");this.addSelectionEvents(b)},addSelectionEvents:function(a){for(var b=!1,c=this,d=q(this.selectable,a),e=d.length;e--;)m(d[e],
"mouseover",function(){for(var a=d.length;a--;)z(d[a],"hover");window.clearTimeout(b)}),m(d[e],"mouseout",function(a){for(a=a.relatedTarget;a&&a.parentNode&&a.className!=this.className;)a=a.parentNode;a&&a.className==this.className||(b=window.setTimeout(function(){for(var a=d.length;a--;)u(d[a],"hover")},2E3))});e=document.createElement("a");e.className="txtsel_close";e.href="#";var f=document.createElement("span");f.className="closewrap";f.appendChild(e);m(e,"click",function(b){D(b);c.deleteSelections([a]);
c.updateHash();c.options.onUnmark&&c.options.onUnmark.call(c)});d[d.length-1].appendChild(f);this.counter++;window.getSelection().removeAllRanges()},getFirstRange:function(){var a=window.getSelection(),a=a.rangeCount?a.getRangeAt(0):null;return this.lastRange&&a&&a.endContainer==a.startContainer&&a.endOffset==a.startOffset?this.lastRange:a},enumerateElements:function(){function a(b){b=b.childNodes;for(var e=!1,f=!1,l=0;l<b.length;++l){var h=b.item(l),g=h.nodeType;if(3!=g||h.nodeValue.match(c.regexp))3==
g?f||(c.captureCount++,e=document.createElement("span"),e.className="masha_index masha_index"+c.captureCount,e.setAttribute("rel",c.captureCount),h.parentNode.insertBefore(e,h),l++,c.blocks[c.captureCount]=h,e=f=!0):1!=g||c.isIgnored(h)||(c.options.isBlock(h)?(h=a(h),e=e||h,f=!1):f||(f=a(h),e=e||f))}return e}var b=this.selectable;this.captureCount=this.captureCount||0;var c=this;a(b)},isFirstTextNode:function(a){a=[a.previousSibling,a.parentNode.previousSibling];for(var b=a.length;b--;)if(a[b]&&1==
a[b].nodeType&&"masha_index"==a[b].className)return!0;return!1},getFirstTextNode:function(a){return a?(a=q(this.selectable,"masha_index"+a)[0])?1==a.nextSibling.nodeType?a.nextSibling.childNodes[0]:a.nextSibling:null:null},getNum:function(a){for(;a.parentNode;){for(;a.previousSibling;){for(a=a.previousSibling;1==a.nodeType&&a.childNodes.length;)a=a.lastChild;if(1==a.nodeType&&k(a,"masha_index"))return a.getAttribute("rel")}a=a.parentNode}return null},constructIgnored:function(a){if("function"==typeof a)return a;
if("string"==typeof a){var b=[],c=[],d=[];a=a.split(",");for(var e=0;e<a.length;e++){var f=w(a[e]);"#"==f.charAt(0)?b.push(f.substr(1)):"."==f.charAt(0)?c.push(f.substr(1)):d.push(f)}return function(a){var e;for(e=b.length;e--;)if(a.id==b[e])return!0;for(e=c.length;e--;)if(k(a,c[e]))return!0;for(e=d.length;e--;)if(a.tagName==d[e].toUpperCase())return!0;return!1}}return function(){return!1}},rangeIsSelectable:function(){var a,b,c,d=!0,e=this.getFirstRange();if(!e)return!1;for(e=e.getElementIterator();a=
e();)if(3==a.nodeType&&null!=a.data.match(this.regexp)&&(b=b||a,c=a),a=d&&3==a.nodeType?a.parentNode:a,d=!1,1==a.nodeType){for(;a!=this.selectable&&a.parentNode;){if(this.isIgnored(a))return!1;a=a.parentNode}if(a!=this.selectable)return!1}b=y(b,"user_selection_true");c=y(c,"user_selection_true");return b&&c?(d=/(?:^| )(num\d+)(?:$| )/,d.exec(b.className)[1]!=d.exec(c.className)[1]):!0},initMessage:function(){this.msg="string"==typeof this.options.selectMessage?document.getElementById(this.options.selectMessage):
this.options.selectMessage;this.close_button=this.getCloseButton();this.msg_autoclose=null;this.closeMessage=p(this.closeMessage,this);m(this.close_button,"click",this.closeMessage)},closeMessage:function(a){D(a);this.hideMessage();this.saveMessageClosed();clearTimeout(this.msg_autoclose)},showMessage:function(){z(this.msg,"show")},hideMessage:function(){u(this.msg,"show")},getCloseButton:function(){return this.msg.getElementsByTagName("a")[0]},getMessageClosed:function(){return window.localStorage?
!!localStorage.masha_warning:!!document.cookie.match(/(?:^|;)\s*masha-warning=/)},saveMessageClosed:function(){window.localStorage?localStorage.masha_warning="true":this.getMessageClosed()||(document.cookie+="; masha-warning=true")},_showMessage:function(){var a=this;this.getMessageClosed()||(this.showMessage(),clearTimeout(this.msg_autoclose),this.msg_autoclose=setTimeout(function(){a.hideMessage()},1E4))}};g=window.Range||document.createRange().constructor;g.prototype.splitBoundaries=function(){var a=
this.startContainer,b=this.startOffset,c=this.endContainer,d=this.endOffset,e=a===c;3==c.nodeType&&d<c.length&&c.splitText(d);3==a.nodeType&&0<b&&(a=a.splitText(b),e&&(d-=b,c=a),b=0);this.setStart(a,b);this.setEnd(c,d)};g.prototype.getTextNodes=function(){for(var a=this.getElementIterator(),b=[],c;c=a();)3==c.nodeType&&b.push(c);return b};g.prototype.getElementIterator=function(a){return a?t(null,this.endContainer,this.startContainer,!0):t(null,this.startContainer,this.endContainer)};g.prototype.getWordIterator=
function(a,b){var c=this.getElementIterator(b),d,e=0,f=0,g=!1,h,k=this;return function(){if(e!=f||g)b?f--:f++;else{do{do d=c();while(d&&3!=d.nodeType);g=!d;if(!g){var m=d.nodeValue;d==k.endContainer&&(m=m.substr(0,k.endOffset));d==k.startContainer&&(m=m.substr(k.startOffset));h=m.match(a)}}while(d&&!h);h&&(e=b?0:h.length-1,f=b?h.length-1:0)}return g?null:h[f]}};g.prototype.wrapSelection=function(a){this.splitBoundaries();for(var b=this.getTextNodes(),c=b.length;c--;){var d=document.createElement("span");
d.className=a;b[c].parentNode.insertBefore(d,b[c]);d.appendChild(b[c])}};var H=function(a){this.prefix=a};H.prototype={setHash:function(a){a=a.replace("sel",this.prefix).replace(/^#/,"");a.length==this.prefix.length+1&&(a="");var b=this.getHashPart();a=b?window.location.hash.replace(b,a):window.location.hash+"|"+a;a="#"+a.replace("||","").replace(/^#?\|?|\|$/g,"");window.location.hash=a},addHashchange:n.LocationHandler.prototype.addHashchange,getHashPart:function(){for(var a=window.location.hash.replace(/^#\|?/,
"").split(/\||%7C/),b=0;b<a.length;b++)if(a[b].substr(0,this.prefix.length+1)==this.prefix+"=")return a[b];return""},getHash:function(){return this.getHashPart().replace(this.prefix,"sel")}};window.MaSha=n;window.jQuery&&(window.jQuery.fn.masha=function(a){a=a||{};a=v({selectable:this[0]},a);return new n(a)});window.MultiMaSha=function(a,b,c){b=b||function(a){return a.id};for(var d=0;d<a.length;d++){var e=a[d],f=b(e);f&&(e=v({},c||{},{selectable:e,location:new H(f)}),new n(e))}};g=n.$M={};g.extend=
v;g.byClassName=q;g.addClass=z;g.removeClass=u;g.addEvent=m;g.removeEvent=r;var E=Function.prototype.bind,F=Array.prototype.slice,p=function(a,b){if(a.bind===E&&E)return E.apply(a,F.call(arguments,1));var c=F.call(arguments,2);return function(){return a.apply(b,c.concat(F.call(arguments)))}};g.bind=p})();


function updateSharePopupContent(){
	var url = StripHTML(window.location.href);
	$("#share-popup .link a").text(url).attr("href",url);
	var b=encodeURI($('#news-title').text());
	var a=encodeURIComponent(url);

	$("#share-popup .social .tw").attr("href","http://twitter.com/share?url="+a+"&text="+b);
	$("#share-popup .social .tw").attr("onclick","window.open('http://twitter.com/share?url="+a+"&text="+b+"','plusshare','width=650,height=400,resizable=1,scrollbars=1,left='+(screen.availWidth/2-325)+',top='+(screen.availHeight/2-200)+'');return false;");
	$("#share-popup .social .fb").attr("href","http://www.facebook.com/share.php?u="+a);
	$("#share-popup .social .fb").attr("onclick","window.open('http://www.facebook.com/share.php?u="+a+"','plusshare','width=650,height=400,resizable=1,scrollbars=1,left='+(screen.availWidth/2-325)+',top='+(screen.availHeight/2-200)+'');return false;");
	$("#share-popup .social .vk").attr("href","http://vk.com/share.php?url="+a);
	$("#share-popup .social .vk").attr("onclick","window.open('http://vk.com/share.php?url="+a+"','plusshare','width=650,height=400,resizable=1,scrollbars=1,left='+(screen.availWidth/2-325)+',top='+(screen.availHeight/2-200)+'');return false;");
	$("#share-popup .social .gp").attr("href","https://plus.google.com/share?url="+ a);
	$("#share-popup .social .gp").attr("onclick","window.open('https://plus.google.com/share?url="+a+"','plusshare','width=650,height=500,resizable=1,scrollbars=1,left='+(screen.availWidth/2-325)+',top='+(screen.availHeight/2-250)+'');return false;");
};

function StripHTML(html) {
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
};

function showSharePopup(b){
	a=$(b).offset();
	$("#share-popup").fadeTo('fast', 1);
	$("#share-popup").addClass('show').css({
            left:a.left+5+$("#share-popup").width()>=$(window).width()?$(window).width()-$("#share-popup").width()-15:a.left+5,
            top:a.top-$("#share-popup").height()-25
	});
};

function hideSharePopup(b,a){
	$("#share-popup").removeClass('show');
	$("#share-popup").hide();
};

jQuery(function($) {
  var timeoutHover = null;
  $("body").append('<div id="share-popup" style="display:none"><div class="social"><p>\u041f\u043e\u0434\u0435\u043b\u0438\u0442\u044c\u0441\u044f \u0441\u0441\u044b\u043b\u043a\u043e\u0439 \u043d\u0430 \u0432\u044b\u0434\u0435\u043b\u0435\u043d\u043d\u044b\u0439 \u0442\u0435\u043a\u0441\u0442</p><ul><li><a href="#" class="tw"><span></span>Twitter</a></li><li><a href="#" class="fb"><span></span>Facebook</a></li><li><a href="#" class="vk"><span></span>\u0412\u043a\u043e\u043d\u0442\u0430\u043a\u0442\u0435</a></li><li><a href="#" class="gp"><span></span>Google+</a></li></ul></div><div class="link"><p>\u041f\u0440\u044f\u043c\u0430\u044f \u0441\u0441\u044b\u043b\u043a\u0430:</p><a href=""><ins></ins></a><span>\u041d\u0430\u0436\u043c\u0438\u0442\u0435 \u043f\u0440\u0430\u0432\u043e\u0439 \u043a\u043b\u0430\u0432\u0438\u0448\u0435\u0439 \u043c\u044b\u0448\u0438 \u0438 \u0432\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u00ab\u041a\u043e\u043f\u0438\u0440\u043e\u0432\u0430\u0442\u044c \u0441\u0441\u044b\u043b\u043a\u0443\u00bb</span></div></div>');
  $("#share-popup").hover(function() {
    if(typeof timeout_hover != "undefined") {
      window.clearTimeout(timeout_hover)
    }
  }, function() {
    timeout_hover = window.setTimeout(function() {
      hideSharePopup()
    }, 2E3)
  });
  MaSha.instance = new MaSha({selectable:$("#dle-content")[0], "ignored":".ignore-select", onMark:function() {
    updateSharePopupContent();
    showSharePopup($(".num" + (this.counter - 1), $(this.selectable))[0])
  }, onUnmark:function() {
    "undefined" != typeof hideSharePopup && hideSharePopup("", !0);
    updateSharePopupContent()
  }});
});