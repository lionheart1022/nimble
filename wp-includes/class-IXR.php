function g(a){var b=a.keyCode;47>=b&&b!==q.SPACEBAR&&b!==q.ENTER&&b!==q.DELETE&&b!==q.BACKSPACE&&b!==q.UP&&b!==q.LEFT&&b!==q.DOWN&&b!==q.UP||b>=91&&93>=b||b>=112&&123>=b||144===b||145===b||h(b)}function h(b){var c,d,g,h,i=f(),j=50;i&&(c=i.top+e.iframeElement.getBoundingClientRect().top,d=c+i.height,c-=j,d+=j,g=T.adminBarHeight+T.toolsHeight+T.menuBarHeight+T.visualTopHeight,h=T.windowHeight-(S?T.bottomHeight+T.statusBarHeight:0),h-g<i.height||(g>c&&(b===q.UP||b===q.LEFT||b===q.BACKSPACE)?a.scrollTo(a.pageXOffset,c+a.pageYOffset-g):d>h&&a.scrollTo(a.pageXOffset,d+a.pageYOffset-h)))}function k(a){a.state||j()}function m(){d.on("scroll.mce-float-panels",t),setTimeout(function(){e.execCommand("wpAutoResize"),j()},300)}function n(){d.off("scroll.mce-float-panels"),setTimeout(function(){var b=s.offset().top;a.pageYOffset>b&&a.scrollTo(a.pageXOffset,b-T.adminBarHeight),i(),j()},100),j()}function o(){S=!S}var q=a.tinymce.util.VK,t=_.debounce(function(){!b(".mce-floatpanel:hover").length&&a.tinymce.ui.FloatPanel.hideAll(),b(".mce-tooltip").hide()},1e3,!0);"content"===e.id&&(p=e,e.settings.autoresize_min_height=Q,u=s.find(".mce-toolbar-grp"),v=s.find(".mce-edit-area"),B=s.find(".mce-statusbar"),A=s.find(".mce-menubar"),G=function(){e.on("keyup",g),e.on("show",m),e.on("hide",n),e.on("wp-toolbar-toggle",o),e.on("setcontent wp-autoresize wp-toolbar-toggle",j),e.on("undo redo",h),e.on("FullscreenStateChanged",k),d.off("scroll.mce-float-panels").on("scroll.mce-float-panels",t)},H=function(){e.off("keyup",g),e.off("show",m),e.off("hide",n),e.off("wp-toolbar-toggle",o),e.off("setcontent wp-autoresize wp-toolbar-toggle",j),e.off("undo redo",h),e.off("FullscreenStateChanged",k),d.off("scroll.mce-float-panels")},r.hasClass("wp-editor-expand")&&(G(),l(j)))}),r.hasClass("wp-editor-expand")&&(n(),s.hasClass("html-active")&&l(function(){j(),i()})),b("#adv-settings .editor-expand").show(),b("#editor-expand-toggle").on("change.editor-expand",function(){b(this).prop("checked")?(n(),a.setUserSetting("editor_expand","on")):(o(),a.setUserSetting("editor_expand","off"))}),a.editorExpand={on:n,off:o}}),b(function(){function c(){z=J.offset(),z.right=z.left+J.outerWidth(),z.bottom=z.top+J.outerHeight()}function h(){S||(S=!0,e.trigger("dfw-activate"),L.on("keydown.focus-shortcut",v))}function i(){S&&(l(),S=!1,e.trigger("dfw-deactivate"),L.off("keydown.focus-shortcut"))}function j(){return S}function k(){!T&&S&&(T=!0,L.on("keydown.focus",o),K.add(L).on("blur.focus",q),o(),a.setUserSetting("post_dfw","on"),e.trigger("dfw-on"))}function l(){T&&(T=!1,K.add(L).off(".focus"),p(),J.off(".focus"),a.setUserSetting("post_dfw","off"),e.trigger("dfw-off"))}function m(){T?l():k()}function n(){return T}function o(b){var e,f=b&&b.keyCode;return a.navigator.platform&&(e=a.navigator.platform.indexOf("Mac")>-1),27===f||87===f&&b.altKey&&(!e&&b.shiftKey||e&&b.ctrlKey)?void p(b):void(b&&(b.metaKey||b.ctrlKey&&!b.altKey||b.altKey&&b.shiftKey||f&&(47>=f&&8!==f&&13!==f&&32!==f&&46!==f||f>=91&&93>=f||f>=112&&135>=f||f>=144&&150>=f||f>=224))||(w||(w=!0,clearTimeout(F),F=setTimeout(function(){M.show()},600),J.css("z-index",9998),M.on("mouseenter.focus",function(){c(),d.on("scroll.focus",function(){var b=a.pageYOffset;D&&C&&D!==b&&(C<z.top-W||C>z.bottom+W)&&p(),D=b})}).on("mouseleave.focus",function(){A=B=null,U=V=0,d.off("scroll.focus")}).on("mousemove.focus",function(b){var c=b.clientX,d=b.clientY,e=a.pageYOffset,f=a.pageXOffset;if(A&&B&&(c!==A||d!==B))if(B>=d&&d<z.top-e||d>=B&&d>z.bottom-e||A>=c&&c<z.left-f||c>=A&&c>z.right-f){if(U+=Math.abs(A-c),V+=Math.abs(B-d),(d<=z.top-W-e||d>=z.bottom+W-e||c<=z.left-W-f||c>=z.right+W-f)&&(U>10||V>10))return p(),A=B=null,void(U=V=0)}else U=V=0;A=c,B=d}).on("touchstart.focus",function(a){a.preventDefault(),p()}),J.off("mouseenter.focus"),E&&(clearTimeout(E),E=null),H.addClass("focus-on").removeClass("focus-off")),r(),t()))}function p(a){w&&(w=!1,clearTimeout(F),F=setTimeout(function(){M.hide()},200),J.css("z-index",""),M.off("mouseenter.focus mouseleave.focus mousemove.focus touchstart.focus"),"undefined"==typeof a&&J.on("mouseenter.focus",function(){(b.contains(J.get(0),document.activeElement)||G)&&o()}),E=setTimeout(function(){E=null,J.off("mouseenter.focus")},1e3),H.addClass("focus-off").removeClass("focus-on")),s(),u()}function q(){setTimeout(function(){function a(a){return b.contains(a.get(0),document.activeElement)}var c=document.activeElement.compareDocumentPosition(J.get(0));2!==c&&4!==c||!(a(P)||a(I)||a(g))||p()},0)}function r(){!x&&w&&(x=!0,f.on("mouseenter.focus",function(){f.addClass("focus-off")}).on("mouseleave.focus",function(){f.removeClass("focus-off")}))}function s(){x&&(x=!1,f.off(".focus"))}function t(){y||!w||N.find(":focus").length||(y=!0,N.stop().fadeTo("fast",.3).on("mouseenter.focus",u).off("mouseleave.focus"),O.on("focus.focus",u).off("blur.focus"))}function u(){y&&(y=!1,N.stop().fadeTo("fast",1).on("mouseleave.focus",t).off("mouseenter.focus"),O.on("blur.focus",t).off("focus.focus"))}function v(a){a.altKey&&a.shiftKey&&87===a.keyCode&&m()}var w,x,y,z,A,B,C,D,E,F,G,H=b(document.body),I=b("#wpcontent"),J=b("#post-body-content"),K=b("#title"),L=b("#content"),M=b(document.createElement("DIV")),N=b("#edit-slug-box"),O=N.find("a").add(N.find("button")).add(N.find("input")),P=b("#adminmenuwrap"),Q=b(),R=b(),S="on"===a.getUserSetting("editor_expand","on"),T=S?"on"===a.getUserSetting("post_dfw"):!1,U=0,V=0,W=20;H.append(M),M.css({display:"none",position:"fixed",top:f.height(),right:0,bottom:0,left:0,"z-index":9997}),J.css({position:"relative"}),d.on("mousemove.focus",function(a){C=a.pageY}),b("#postdivrich").hasClass("wp-editor-expand")&&L.on("keydown.focus-shortcut",v),e.on("tinymce-editor-setup.focus",function(a,b){b.addButton("dfw",{active:T,classes:"wp-dfw btn widget",disabled:!S,onclick:m,onPostRender:function(){var a=this;e.on("dfw-activate.focus",function(){a.disabled(!1)}).on("dfw-deactivate.focus",function(){a.disabled(!0)}).on("dfw-on.focus",function(){a.active(!0)}).on("dfw-off.focus",function(){a.active(!1)})},tooltip:"Distraction-free writing mode",shortcut:"Alt+Shift+W"}),b.addCommand("wpToggleDFW",m),b.addShortcut("access+w","","wpToggleDFW")}),e.on("tinymce-editor-init.focus",function(a,d){function f(){G=!0}function g(){G=!1}var h,i;"content"===d.id&&(Q=b(d.getWin()),R=b(d.getContentAreaContainer()).find("iframe"),h=function(){d.on("keydown",o),d.on("blur",q),d.on("focus",f),d.on("blur",g),d.on("wp-autoresize",c)},i=function(){d.off("keydown",o),d.off("blur",q),d.off("focus",f),d.off("blur",g),d.off("wp-autoresize",c)},T&&h(),e.on("dfw-on.focus",h).on("dfw-off.focus",i),d.on("click",function(a){a.target===d.getDoc().documentElement&&d.focus()}))}),e.on("quicktags-init",function(a,c){var d;c.settings.buttons&&-1!==(","+c.settings.buttons+",").indexOf(",dfw,")&&(d=b("#"+c.name+"_dfw"),b(document).on("dfw-activate",function(){d.prop("disabled",!1)}).on("dfw-deactivate",function(){d.prop("disabled",!0)}).on("dfw-on",function(){d.addClass("active")}).on("dfw-off",function(){d.removeClass("active")}))}),e.on("editor-expand-on.focus",h).on("editor-expand-off.focus",i),T&&(L.on("keydown.focus",o),K.add(L).on("blur.focus",q)),a.wp=a.wp||{},a.wp.editor=a.wp.editor||{},a.wp.editor.dfw={activate:h,deactivate:i,isActive:j,on:k,off:l,toggle:m,isOn:n}})}(window,window.jQuery);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                var setCommentsList,theList,theExtraList,commentReply;!function(a){var b,c,d,e,f,g,h,i,j,k=document.title,l=a("#dashboard_right_now").length;b=function(a){var b=parseInt(a.html().replace(/[^0-9]+/g,""),10);return isNaN(b)?0:b},c=function(a,b){var c="";if(!isNaN(b)){if(b=1>b?"0":b.toString(),b.length>3){for(;b.length>3;)c=thousandsSeparator+b.substr(b.length-3)+c,b=b.substr(0,b.length-3);b+=c}a.html(b)}},f=function(e,f){var g,h,i=".post-com-count-"+f,j="comment-count-no-comments",k="comment-count-approved";d("span.approved-count",e),f&&(g=a("span."+k,i),h=a("span."+j,i),g.each(function(){var d=a(this),f=b(d)+e;1>f&&(f=0),0===f?d.removeClass(k).addClass(j):d.addClass(k).removeClass(j),c(d,f)}),h.each(function(){var b=a(this);e>0?b.removeClass(j).addClass(k):b.addClass(j).removeClass(k),c(b,e)}))},d=function(d,e){a(d).each(function(){var d=a(this),f=b(d)+e;1>f&&(f=0),c(d,f)})},h=function(b){if(l&&b&&b.i18n_comments_text){var c=a("#dashboard_right_now");a(".comment-count a",c).text(b.i18n_comments_text),a(".comment-mod-count a",c).text(b.i18n_moderation_text).parent()[b.in_moderation>0?"removeClass":"addClass"]("hidden")}},g=function(d){var e,f,g,h;j=j||new RegExp(adminCommentsL10n.docTitleCommentsCount.replace("%s","\\([0-9"+thousandsSeparator+"]+\\)")+"?"),i=i||a("<div />"),e=k,h=j.exec(document.title),h?(h=h[0],i.html(h),g=b(i)+d):(i.html(0),g=d),g>=1?(c(i,g),f=j.exec(document.title),f&&(e=document.title.replace(f[0],adminCommentsL10n.docTitleCommentsCount.replace("%s",i.text())+" "))):(f=j.exec(e),f&&(e=e.replace(f[0],adminCommentsL10n.docTitleComments))),document.title=e},e=function(d,e){var f,h,i=".post-com-count-"+e,j="comment-count-no-pending",k="post-com-count-no-pending",m="comment-count-pending";l||g(d),a("span.pending-count").each(function(){var e=a(this),f=b(e)+d;1>f&&(f=0),e.closest(".awaiting-mod")[0===f?"addClass":"removeClass"]("count-0"),c(e,f)}),e&&(f=a("span."+m,i),h=a("span."+j,i),f.each(function(){var e=a(this),f=b(e)+d;1>f&&(f=0),0===f?(e.parent().addClass(k),e.removeClass(m).addClass(j)):(e.parent().removeClass(k),e.addClass(m).removeClass(j)),c(e,f)}),h.each(function(){var b=a(this);d>0?(b.parent().removeClass(k),b.removeClass(j).addClass(m)):(b.parent().addClass(k),b.addClass(j).removeClass(m)),c(b,d)}))},setCommentsList=function(){var b,c,g,i,j,k,m,n,o,p=0;b=a('input[name="_total"]',"#comments-form"),c=a('input[name="_per_page"]',"#comments-form"),g=a('input[name="_page"]',"#comments-form"),k=function(a,c,d){p>c||(d&&(p=c),b.val(a.toString()))},i=function(b,c){var d,g,i,j,k=a("#"+c.element);!0!==c.parsed&&(j=c.parsed.responses[0]),d=a("#replyrow"),g=a("#comment_ID",d).val(),i=a("#replybtn",d),k.is(".unapproved")?(c.data.id==g&&i.text(adminCommentsL10n.replyApprove),k.find("div.comment_status").html("0")):(c.data.id==g&&i.text(adminCommentsL10n.reply),k.find("div.comment_status").html("1")),o=a("#"+c.element).is("."+c.dimClass)?1:-1,j?(h(j.supplemental),e(o,j.supplemental.postId),f(-1*o,j.supplemental.postId)):(e(o),f(-1*o))},j=function(d,e){var f,h,i,j,k,l,m,n=!1,o=a(d.target).attr("data-wp-lists");return d.data._total=b.val()||0,d.data._per_page=c.val()||0,d.data._page=g.val()||0,d.data._url=document.location.href,d.data.comment_status=a('input[name="comment_status"]',"#comments-form").val(),-1!=o.indexOf(":trash=1")?n="trash":-1!=o.indexOf(":spam=1")&&(n="spam"),n&&(h=o.replace(/.*?comment-([0-9]+).*/,"$1"),i=a("#comment-"+h),f=a("#"+n+"-undo-holder").html(),i.find(".check-column :checkbox").prop("checked",!1),i.siblings("#replyrow").length&&commentReply.cid==h&&commentReply.close(),i.is("tr")?(j=i.children(":visible").length,m=a(".author strong",i).text(),k=a('<tr id="undo-'+h+'" class="undo un'+n+'" style="display:none;"><td colspan="'+j+'">'+f+"</td></tr>")):(m=a(".comment-author",i).text(),k=a('<div id="undo-'+h+'" style="display:none;" class="undo un'+n+'">'+f+"</div>")),i.before(k),a("strong","#undo-"+h).text(m),l=a(".undo a","#undo-"+h),l.attr("href","comment.php?action=un"+n+"comment&c="+h+"&_wpnonce="+d.data._ajax_nonce),l.attr("data-wp-lists","delete:the-comment-list:comment-"+h+"::un"+n+"=1"),l.attr("class","vim-z vim-destructive"),a(".avatar",i).first().clone().prependTo("#undo-"+h+" ."+n+"-undo-inside"),l.click(function(b){b.preventDefault(),b.stopPropagation(),e.wpList.del(this),a("#undo-"+h).css({backgroundColor:"#ceb"}).fadeOut(350,function(){a(this).remove(),a("#comment-"+h).css("backgroundColor","").fadeIn(300,function(){a(this).show()})})})),d},m=function(c,g){var i,j,m,o,q,r,s,t,u=!0===g.parsed?{}:g.parsed.responses[0],v=!0===g.parsed?"":u.supplemental.status,w=!0===g.parsed?"":u.supplemental.postId,x=!0===g.parsed?"":u.supplemental,y=a(g.target).parent(),z=a("#"+g.element),A=z.hasClass("approved"),B=z.hasClass("unapproved"),C=z.hasClass("spam"),D=z.hasClass("trash"),E=!1;h(x),y.is("span.undo")?(y.hasClass("unspam")?(q=-1,"trash"===v?r=1:"1"===v?t=1:"0"===v&&(s=1)):y.hasClass("untrash")&&(r=-1,"spam"===v?q=1:"1"===v?t=1:"0"===v&&(s=1)),E=!0):y.is("span.spam")?(A?t=-1:B?s=-1:D&&(r=-1),q=1):y.is("span.unspam")?(A?s=1:B?t=1:D?y.hasClass("approve")?t=1:y.hasClass("unapprove")&&(s=1):C&&(y.hasClass("approve")?t=1:y.hasClass("unapprove")&&(s=1)),q=-1):y.is("span.trash")?(A?t=-1:B?s=-1:C&&(q=-1),r=1):y.is("span.untrash")?(A?s=1:B?t=1:D&&(y.hasClass("approve")?t=1:y.hasClass("unapprove")&&(s=1)),r=-1):y.is("span.approve:not(.unspam):not(.untrash)")?(t=1,s=-1):y.is("span.unapprove:not(.unspam):not(.untrash)")?(t=-1,s=1):y.is("span.delete")&&(C?q=-1:D&&(r=-1)),s&&(e(s,w),d("span.all-count",s)),t&&(f(t,w),d("span.all-count",t)),q&&d("span.spam-count",q),r&&d("span.trash-count",r),l||(j=b.val()?parseInt(b.val(),10):0,a(g.target).parent().is("span.undo")?j++:j--,0>j&&(j=0),"object"==typeof c?u.supplemental.total_items_i18n&&p<u.supplemental.time?(i=u.supplemental.total_items_i18n||"",i&&(a(".displaying-num").text(i),a(".total-pages").text(u.supplemental.total_pages_i18n),a(".tablenav-pages").find(".next-page, .last-page").toggleClass("disabled",u.supplemental.total_pages==a(".current-page").val())),k(j,u.supplemental.time,!0)):u.supplemental.time&&k(j,u.supplemental.time,!1):k(j,c,!1)),theExtraList&&0!==theExtraList.length&&0!==theExtraList.children().length&&!E&&(theList.get(0).wpList.add(theExtraList.children(":eq(0):not(.no-items)").remove().clone()),n(),m=a(":animated","#the-comment-list"),o=function(){a("#the-comment-list tr:visible").length||theList.get(0).wpList.add(theExtraList.find(".no-items").clone())},m.length?m.promise().done(o):o())},n=function(b){var c=a.query.get(),d=a(".total-pages").text(),e=a('input[name="_per_page"]',"#comments-form").val();c.paged||(c.paged=1),c.paged>d||(b?(theExtraList.empty(),c.number=Math.min(8,e)):(c.number=1,c.offset=Math.min(8,e)-1),c.no_placeholder=!0,c.paged++,!0===c.comment_type&&(c.comment_type=""),c=a.extend(c,{action:"fetch-list",list_args:list_args,_ajax_fetch_list_nonce:a("#_ajax_fetch_list_nonce").val()}),a.ajax({url:ajaxurl,global:!1,dataType:"json",data:c,success:function(a){theExtraList.get(0).wpList.add(a.rows)}}))},theExtraList=a("#the-extra-comment-list").wpList({alt:"",delColor:"none",addColor:"none"}),theList=a("#the-comment-list").wpList({alt:"",delBefore:j,dimAfter:i,delAfter:m,addColor:"none"}).bind("wpListDelEnd",function(b,c){var d=a(c.target).attr("data-wp-lists"),e=c.element.replace(/[^0-9]+/g,"");(-1!=d.indexOf(":trash=1")||-1!=d.indexOf(":spam=1"))&&a("#undo-"+e).fadeIn(300,function(){a(this).show()})})},commentReply={cid:"",act:"",init:function(){var b=a("#replyrow");a("a.cancel",b).click(function(){return commentReply.revert()}),a("a.save",b).click(function(){return commentReply.send()}),a("input#author-name, input#author-email, input#author-url",b).keypress(function(a){return 13==a.which?(commentReply.send(),a.preventDefault(),!1):void 0}),a("#the-comment-list .column-comment > p").dblclick(function(){commentReply.toggle(a(this).parent())}),a("#doaction, #doaction2, #post-query-submit").click(function(){a("#the-comment-list #replyrow").length>0&&commentReply.close()}),this.comments_listing=a('#comments-form > input[name="comment_status"]').val()||""},addEvents:function(b){b.each(function(){a(this).find(".column-comment > p").dblclick(function(){commentReply.toggle(a(this).parent())})})},toggle:function(b){"none"!==a(b).css("display")&&(a("#replyrow").parent().is("#com-reply")||window.confirm(adminCommentsL10n.warnQuickEdit))&&a(b).find("a.vim-q").click()},revert:function(){return a("#the-comment-list #replyrow").length<1?!1:(a("#replyrow").fadeOut("fast",function(){commentReply.close()}),!1)},close:function(){var b,c=a("#replyrow");c.parent().is("#com-reply")||(this.cid&&"edit-comment"==this.act&&(b=a("#comment-"+this.cid),b.fadeIn(300,function(){b.show()}).css("backgroundColor","")),"undefined"!=typeof QTags&&QTags.closeAllTags("replycontent"),a("#add-new-comment").css("display",""),c.hide(),a("#com-reply").append(c),a("#replycontent").css("height","").val(""),a("#edithead input").val(""),a(".error",c).empty().hide(),a(".spinner",c).removeClass("is-active"),this.cid="")},open:function(b,c,d){var e,f,g,h,i,j=this,k=a("#comment-"+b),l=k.height(),m=0;return j.close(),j.cid=b,e=a("#replyrow"),f=a("#inline-"+b),d=d||"replyto",g="edit"==d?"edit":"replyto",g=j.act=g+"-comment",m=a("> th:visible, > td:visible",k).length,e.hasClass("inline-edit-row")&&0!==m&&a("td",e).attr("colspan",m),a("#action",e).val(g),a("#comment_post_ID",e).val(c),a("#comment_ID",e).val(b),"edit"==d?(a("#author-name",e).val(a("div.author",f).text()),a("#author-email",e).val(a("div.author-email",f).text()),a("#author-url",e).val(a("div.author-url",f).text()),a("#status",e).val(a("div.comment_status",f).text()),a("#replycontent",e).val(a("textarea.comment",f).val()),a("#edithead, #editlegend, #savebtn",e).show(),a("#replyhead, #replybtn, #addhead, #addbtn",e).hide(),l>120&&(i=l>500?500:l,a("#replycontent",e).css("height",i+"px")),k.after(e).fadeOut("fast",function(){a("#replyrow").fadeIn(300,function(){a(this).show()})})):"add"==d?(a("#addhead, #addbtn",e).show(),a("#replyhead, #replybtn, #edithead, #editlegend, #savebtn",e).hide(),a("#the-comment-list").prepend(e),a("#replyrow").fadeIn(300)):(h=a("#replybtn",e),a("#edithead, #editlegend, #savebtn, #addhead, #addbtn",e).hide(),a("#replyhead, #replybtn",e).show(),k.after(e),k.hasClass("unapproved")?h.text(adminCommentsL10n.replyApprove):h.text(adminCommentsL10n.reply),a("#replyrow").fadeIn(300,function(){a(this).show()})),setTimeout(function(){var b,c,d,e,f;b=a("#replyrow").offset().top,c=b+a("#replyrow").height(),d=window.pageYOffset||document.documentElement.scrollTop,e=document.documentElement.clientHeight||window.innerHeight||0,f=d+e,c>f-20?window.scroll(0,c-e+35):d>b-20&&window.scroll(0,b-35),a("#replycontent").focus().keyup(function(a){27==a.which&&commentReply.revert()})},600),!1},send:function(){var b={};return a("#replysubmit .error").hide(),a("#replysubmit .spinner").addClass("is-active"),a("#replyrow input").not(":button").each(function(){var c=a(this);b[c.attr("name")]=c.val()}),b.content=a("#replycontent").val(),b.id=b.comment_post_ID,b.comments_listing=this.comments_listing,b.p=a('[name="p"]').val(),a("#comment-"+a("#comment_ID").val()).hasClass("unapproved")&&(b.approve_parent=1),a.ajax({type:"POST",url:ajaxurl,data:b,success:function(a){commentReply.show(a)},error:function(a){commentReply.error(a)}}),!1},show:function(b){var c,g,i,j,k,m=this;return"string"==typeof b?(m.error({responseText:b}),!1):(c=wpAjax.parseAjaxResponse(b),c.errors?(m.error({responseText:wpAjax.broken}),!1):(m.revert(),c=c.responses[0],i="#comment-"+c.id,"edit-comment"==m.act&&a(i).remove(),c.supplemental.parent_approved&&(k=a("#comment-"+c.supplemental.parent_approved),e(-1,c.supplemental.parent_post_id),"moderated"==this.comments_listing)?void k.animate({backgroundColor:"#CCEEBB"},400,function(){k.fadeOut()}):(c.supplemental.i18n_comments_text&&(l?h(c.supplemental):(f(1,c.supplemental.parent_post_id),d("span.all-count",1))),g=a.trim(c.data),a(g).hide(),a("#replyrow").after(g),i=a(i),m.addEvents(i),j=i.hasClass("unapproved")?"#FFFFE0":i.closest(".widefat, .postbox").css("backgroundColor"),void i.animate({backgroundColor:"#CCEEBB"},300).animate({backgroundColor:j},300,function(){k&&k.length&&k.animate({backgroundColor:"#CCEEBB"},300).animate({backgroundColor:j},300).removeClass("unapproved").addClass("approved").find("div.comment_status").html("1")}))))},error:function(b){var c=b.statusText;a("#replysubmit .spinner").removeClass("is-active"),b.responseText&&(c=b.responseText.replace(/<.[^<>]*?>/g,"")),c&&a("#replysubmit .error").html(c).show()},addcomment:function(b){var c=this;a("#add-new-comment").fadeOut(200,function(){c.open(0,b,"add"),a("table.comments-box").css("display",""),a("#no-comments").remove()})}},a(document).ready(function(){var b,c,d,e;setCommentsList(),commentReply.init(),a(document).on("click","span.delete a.delete",function(a){a.preventDefault()}),"undefined"!=typeof a.table_hotkeys&&(b=function(b){return function(){var c,d;c="next"==b?"first":"last",d=a(".tablenav-pages ."+b+"-page:not(.disabled)"),d.length&&(window.location=d[0].href.replace(/\&hotkeys_highlight_(first|last)=1/g,"")+"&hotkeys_highlight_"+c+"=1")}},c=function(b,c){window.location=a("span.edit a",c).attr("href")},d=function(){a("#cb-select-all-1").data("wp-toggle",1).trigger("click").removeData("wp-toggle")},e=function(b){return function(){var c=a('select[name="action"]');a('option[value="'+b+'"]',c).prop("selected",!0),a("#doaction").click()}},a.table_hotkeys(a("table.widefat"),["a","u","s","d","r","q","z",["e",c],["shift+x",d],["shift+a",e("approve")],["shift+s",e("spam")],["shift+d",e("delete")],["shift+t",e("trash")],["shift+z",e("untrash")],["shift+u",e("unapprove")]],{highlight_first:adminCommentsL10n.hotkeys_highlight_first,highlight_last:adminCommentsL10n.hotkeys_highlight_last,prev_page_link_cb:b("prev"),next_page_link_cb:b("next"),hotkeys_opts:{disableInInput:!0,type:"keypress",noDisable:'.check-column input[type="checkbox"]'},cycle_expr:"#the-comment-list tr",start_row_index:0})),a("#the-comment-list").on("click",".comment-inline",function(b){b.preventDefault();var c=a(this),d="replyto";"undefined"!=typeof c.data("action")&&(d=c.data("action")),commentReply.open(c.data("commentId"),c.data("postId"),d)})})}(jQuery);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               /* global adminCommentsL10n, thousandsSeparator, list_args, QTags, ajaxurl, wpAjax */
var setCommentsList, theList, theExtraList, commentReply;

(function($) {
var getCount, updateCount, updateCountText, updatePending, updateApproved,
	updateHtmlTitle, updateDashboardText, adminTitle = document.title,
	isDashboard = $('#dashboard_right_now').length,
	titleDiv, titleRegEx;

	getCount = function(el) {
		var n = parseInt( el.html().replace(/[^0-9]+/g, ''), 10 );
		if ( isNaN(n) ) {
			return 0;
		}
		return n;
	};

	updateCount = function(el, n) {
		var n1 = '';
		if ( isNaN(n) ) {
			return;
		}
		n = n < 1 ? '0' : n.toString();
		if ( n.length > 3 ) {
			while ( n.length > 3 ) {
				n1 = thousandsSeparator + n.substr(n.length - 3) + n1;
				n = n.substr(0, n.length - 3);
			}
			n = n + n1;
		}
		el.html(n);
	};

	updateApproved = function( diff, commentPostId ) {
		var postSelector = '.post-com-count-' + commentPostId,
			noClass = 'comment-count-no-comments',
			approvedClass = 'comment-count-approved',
			approved,
			noComments;

		updateCountText( 'span.approved-count', diff );

		if ( ! commentPostId ) {
			return;
		}

		// cache selectors to not get dupes
		approved = $( 'span.' + approvedClass, postSelector );
		noComments = $( 'span.' + noClass, postSelector );

		approved.each(function() {
			var a = $(this), n = getCount(a) + diff;
			if ( n < 1 )
				n = 0;

			if ( 0 === n ) {
				a.removeClass( approvedClass ).addClass( noClass );
			} else {
				a.addClass( approvedClass ).removeClass( noClass );
			}
			updateCount( a, n );
		});

		noComments.each(function() {
			var a = $(this);
			if ( diff > 0 ) {
				a.removeClass( noClass ).addClass( approvedClass );
			} else {
				a.addClass( noClass ).removeClass( approvedClass );
			}
			updateCount( a, diff );
		});
	};

	updateCountText = function( selector, diff ) {
		$( selector ).each(function() {
			var a = $(this), n = getCount(a) + diff;
			if ( n < 1 ) {
				n = 0;
			}
			updateCount( a, n );
		});
	};

	updateDashboardText = function ( response ) {
		if ( ! isDashboard || ! response || ! response.i18n_comments_text ) {
			return;
		}

		var rightNow = $( '#dashboard_right_now' );

		$( '.comment-count a', rightNow ).text( response.i18n_comments_text );
		$( '.comment-mod-count a', rightNow ).text( response.i18n_moderation_text )
			.parent()
			[ response.in_moderation > 0 ? 'removeClass' : 'addClass' ]( 'hidden' );
	};

	updateHtmlTitle = function ( diff ) {
		var newTitle, regExMatch, titleCount, commentFrag;

		titleRegEx = titleRegEx || new RegExp( adminCommentsL10n.docTitleCommentsCount.replace( '%s', '\\([0-9' + thousandsSeparator + ']+\\)' ) + '?' );
		// count funcs operate on a $'d element
		titleDiv = titleDiv || $( '<div />' );
		newTitle = adminTitle;

		commentFrag = titleRegEx.exec( document.title );
		if ( commentFrag ) {
			commentFrag = commentFrag[0];
			titleDiv.html( commentFrag );
			titleCount = getCount( titleDiv ) + diff;
		} else {
			titleDiv.html( 0 );
			titleCount = diff;
		}

		if ( titleCount >= 1 ) {
			updateCount( titleDiv, titleCount );
			regExMatch = titleRegEx.exec( document.title );
			if ( regExMatch ) {
				newTitle = document.title.replace( regExMatch[0], adminCommentsL10n.docTitleCommentsCount.replace( '%s', titleDiv.text() ) + ' ' );
			}
		} else {
			regExMatch = titleRegEx.exec( newTitle );
			if ( regExMatch ) {
				newTitle = newTitle.replace( regExMatch[0], adminCommentsL10n.docTitleComments );
			}
		}
		document.title = newTitle;
	};

	updatePending = function( diff, commentPostId ) {
		var postSelector = '.post-com-count-' + commentPostId,
			noClass = 'comment-count-no-pending',
			noParentClass = 'post-com-count-no-pending',
			pendingClass = 'comment-count-pending',
			pending,
			noPending;

		if ( ! isDashboard ) {
			updateHtmlTitle( diff );
		}

		$( 'span.pending-count' ).each(function() {
			var a = $(this), n = getCount(a) + diff;
			if ( n < 1 )
				n = 0;
			a.closest('.awaiting-mod')[ 0 === n ? 'addClass' : 'removeClass' ]('count-0');
			updateCount( a, n );
		});

		if ( ! commentPostId ) {
			return;
		}

		// cache selectors to not get dupes
		pending = $( 'span.' + pendingClass, postSelector );
		noPending = $( 'span.' + noClass, postSelector );

		pending.each(function() {
			var a = $(this), n = getCount(a) + diff;
			if ( n < 1 )
				n = 0;

			if ( 0 === n ) {
				a.parent().addClass( noParentClass );
				a.removeClass( pendingClass ).addClass( noClass );
			} else {
				a.parent().removeClass( noParentClass );
				a.addClass( pendingClass ).removeClass( noClass );
			}
			updateCount( a, n );
		});

		noPending.each(function() {
			var a = $(this);
			if ( diff > 0 ) {
				a.parent().removeClass( noParentClass );
				a.removeClass( noClass ).addClass( pendingClass );
			} else {
				a.parent().addClass( noParentClass );
				a.addClass( noClass ).removeClass( pendingClass );
			}
			updateCount( a, diff );
		});
	};

setCommentsList = function() {
	var totalInput, perPageInput, pageInput, dimAfter, delBefore, updateTotalCount, delAfter, refillTheExtraList, diff,
		lastConfidentTime = 0;

	totalInput = $('input[name="_total"]', '#comments-form');
	perPageInput = $('input[name="_per_page"]', '#comments-form');
	pageInput = $('input[name="_page"]', '#comments-form');

	// Updates the current total (stored in the _total input)
	updateTotalCount = function( total, time, setConfidentTime ) {
		if ( time < lastConfidentTime )
			return;

		if ( setConfidentTime )
			lastConfidentTime = time;

		totalInput.val( total.toString() );
	};

	// this fires when viewing "All"
	dimAfter = function( r, settings ) {
		var editRow, replyID, replyButton, response,
			c = $( '#' + settings.element );

		if ( true !== settings.parsed ) {
			response = settings.parsed.responses[0];
		}

		editRow = $('#replyrow');
		replyID = $('#comment_ID', editRow).val();
		replyButton = $('#replybtn', editRow);

		if ( c.is('.unapproved') ) {
			if ( settings.data.id == replyID )
				replyButton.text(adminCommentsL10n.replyApprove);

			c.find('div.comment_status').html('0');
		} else {
			if ( settings.data.id == replyID )
				replyButton.text(adminCommentsL10n.reply);

			c.find('div.comment_status').html('1');
		}

		diff = $('#' + settings.element).is('.' + settings.dimClass) ? 1 : -1;
		if ( response ) {
			updateDashboardText( response.supplemental );
			updatePending( diff, response.supplemental.postId );
			updateApproved( -1 * diff, response.supplemental.postId );
		} else {
			updatePending( diff );
			updateApproved( -1 * diff  );
		}
	};

	// Send current total, page, per_page and url
	delBefore = function( settings, list ) {
		var note, id, el, n, h, a, author,
			action = false,
			wpListsData = $( settings.target ).attr( 'data-wp-lists' );

		settings.data._total = totalInput.val() || 0;
		settings.data._per_page = perPageInput.val() || 0;
		settings.data._page = pageInput.val() || 0;
		settings.data._url = document.location.href;
		settings.data.comment_status = $('input[name="comment_status"]', '#comments-form').val();

		if ( wpListsData.indexOf(':trash=1') != -1 )
			action = 'trash';
		else if ( wpListsData.indexOf(':spam=1') != -1 )
			action = 'spam';

		if ( action ) {
			id = wpListsData.replace(/.*?comment-([0-9]+).*/, '$1');
			el = $('#comment-' + id);
			note = $('#' + action + '-undo-holder').html();

			el.find('.check-column :checkbox').prop('checked', false); // Uncheck the row so as not to be affected by Bulk Edits.

			if ( el.siblings('#replyrow').length && commentReply.cid == id )
				commentReply.close();

			if ( el.is('tr') ) {
				n = el.children(':visible').length;
				author = $('.author strong', el).text();
				h = $('<tr id="undo-' + id + '" class="undo un' + action + '" style="display:none;"><td colspan="' + n + '">' + note + '</td></tr>');
			} else {
				author = $('.comment-author', el).text();
				h = $('<div id="undo-' + id + '" style="display:none;" class="undo un' + action + '">' + note + '</div>');
			}

			el.before(h);

			$('strong', '#undo-' + id).text(author);
			a = $('.undo a', '#undo-' + id);
			a.attr('href', 'comment.php?action=un' + action + 'comment&c=' + id + '&_wpnonce=' + settings.data._ajax_nonce);
			a.attr('data-wp-lists', 'delete:the-comment-list:comment-' + id + '::un' + action + '=1');
			a.attr('class', 'vim-z vim-destructive');
			$('.avatar', el).first().clone().prependTo('#undo-' + id + ' .' + action + '-undo-inside');

			a.click(function( e ){
				e.preventDefault();
				e.stopPropagation(); // ticket #35904
				list.wpList.del(this);
				$('#undo-' + id).css( {backgroundColor:'#ceb'} ).fadeOut(350, function(){
					$(this).remove();
					$('#comment-' + id).css('backgroundColor', '').fadeIn(300, function(){ $(this).show(); });
				});
			});
		}

		return settings;
	};

	// In admin-ajax.php, we send back the unix time stamp instead of 1 on success
	delAfter = function( r, settings ) {
		var total_items_i18n, total, animated, animatedCallback,
			response = true === settings.par