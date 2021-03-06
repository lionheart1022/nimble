	pendingDiff = -1;
			// the comment was in the trash
			} else if ( trashed ) {
				trashDiff = -1;
			}
			// you can't spam an item on the spam screen
			spamDiff = 1;

		// user clicked "Unspam"
		} else if ( targetParent.is( 'span.unspam' ) ) {
			if ( approved ) {
				pendingDiff = 1;
			} else if ( unapproved ) {
				approvedDiff = 1;
			} else if ( trashed ) {
				// the comment was previously approved
				if ( targetParent.hasClass( 'approve' ) ) {
					approvedDiff = 1;
				// the comment was previously pending
				} else if ( targetParent.hasClass( 'unapprove' ) ) {
					pendingDiff = 1;
				}
			} else if ( spammed ) {
				if ( targetParent.hasClass( 'approve' ) ) {
					approvedDiff = 1;

				} else if ( targetParent.hasClass( 'unapprove' ) ) {
					pendingDiff = 1;
				}
			}
			// you can Unspam an item on the spam screen
			spamDiff = -1;

		// user clicked "Trash"
		} else if ( targetParent.is( 'span.trash' ) ) {
			if ( approved ) {
				approvedDiff = -1;
			} else if ( unapproved ) {
				pendingDiff = -1;
			// the comment was in the spam queue
			} else if ( spammed ) {
				spamDiff = -1;
			}
			// you can't trash an item on the trash screen
			trashDiff = 1;

		// user clicked "Restore"
		} else if ( targetParent.is( 'span.untrash' ) ) {
			if ( approved ) {
				pendingDiff = 1;
			} else if ( unapproved ) {
				approvedDiff = 1;
			} else if ( trashed ) {
				if ( targetParent.hasClass( 'approve' ) ) {
					approvedDiff = 1;
				} else if ( targetParent.hasClass( 'unapprove' ) ) {
					pendingDiff = 1;
				}
			}
			// you can't go from trash to spam
			// you can untrash on the trash screen
			trashDiff = -1;

		// User clicked "Approve"
		} else if ( targetParent.is( 'span.approve:not(.unspam):not(.untrash)' ) ) {
			approvedDiff = 1;
			pendingDiff = -1;

		// User clicked "Unapprove"
		} else if ( targetParent.is( 'span.unapprove:not(.unspam):not(.untrash)' ) ) {
			approvedDiff = -1;
			pendingDiff = 1;

		// User clicked "Delete Permanently"
		} else if ( targetParent.is( 'span.delete' ) ) {
			if ( spammed ) {
				spamDiff = -1;
			} else if ( trashed ) {
				trashDiff = -1;
			}
		}

		if ( pendingDiff ) {
			updatePending( pendingDiff, commentPostId );
			updateCountText( 'span.all-count', pendingDiff );
		}

		if ( approvedDiff ) {
			updateApproved( approvedDiff, commentPostId );
			updateCountText( 'span.all-count', approvedDiff );
		}

		if ( spamDiff ) {
			updateCountText( 'span.spam-count', spamDiff );
		}

		if ( trashDiff ) {
			updateCountText( 'span.trash-count', trashDiff );
		}

		if ( ! isDashboard ) {
			total = totalInput.val() ? parseInt( totalInput.val(), 10 ) : 0;
			if ( $(settings.target).parent().is('span.undo') )
				total++;
			else
				total--;

			if ( total < 0 )
				total = 0;

			if ( 'object' === typeof r ) {
				if ( response.supplemental.total_items_i18n && lastConfidentTime < response.supplemental.time ) {
					total_items_i18n = response.supplemental.total_items_i18n || '';
					if ( total_items_i18n ) {
						$('.displaying-num').text( total_items_i18n );
						$('.total-pages').text( response.supplemental.total_pages_i18n );
						$('.tablenav-pages').find('.next-page, .last-page').toggleClass('disabled', response.supplemental.total_pages == $('.current-page').val());
					}
					updateTotalCount( total, response.supplemental.time, true );
				} else if ( response.supplemental.time ) {
					updateTotalCount( total, response.supplemental.time, false );
				}
			} else {
				updateTotalCount( total, r, false );
			}
		}

		if ( ! theExtraList || theExtraList.length === 0 || theExtraList.children().length === 0 || undoing ) {
			return;
		}

		theList.get(0).wpList.add( theExtraList.children( ':eq(0):not(.no-items)' ).remove().clone() );

		refillTheExtraList();

		animated = $( ':animated', '#the-comment-list' );
		animatedCallback = function () {
			if ( ! $( '#the-comment-list tr:visible' ).length ) {
				theList.get(0).wpList.add( theExtraList.find( '.no-items' ).clone() );
			}
		};

		if ( animated.length ) {
			animated.promise().done( animatedCallback );
		} else {
			animatedCallback();
		}
	};

	refillTheExtraList = function(ev) {
		var args = $.query.get(), total_pages = $('.total-pages').text(), per_page = $('input[name="_per_page"]', '#comments-form').val();

		if (! args.paged)
			args.paged = 1;

		if (args.paged > total_pages) {
			return;
		}

		if (ev) {
			theExtraList.empty();
			args.number = Math.min(8, per_page); // see WP_Comments_List_Table::prepare_items() @ class-wp-comments-list-table.php
		} else {
			args.number = 1;
			args.offset = Math.min(8, per_page) - 1; // fetch only the next item on the extra list
		}

		args.no_placeholder = true;

		args.paged ++;

		// $.query.get() needs some correction to be sent into an ajax request
		if ( true === args.comment_type )
			args.comment_type = '';

		args = $.extend(args, {
			'action': 'fetch-list',
			'list_args': list_args,
			'_ajax_fetch_list_nonce': $('#_ajax_fetch_list_nonce').val()
		});

		$.ajax({
			url: ajaxurl,
			global: false,
			dataType: 'json',
			data: args,
			success: function(response) {
				theExtraList.get(0).wpList.add( response.rows );
			}
		});
	};

	theExtraList = $('#the-extra-comment-list').wpList( { alt: '', delColor: 'none', addColor: 'none' } );
	theList = $('#the-comment-list').wpList( { alt: '', delBefore: delBefore, dimAfter: dimAfter, delAfter: delAfter, addColor: 'none' } )
		.bind('wpListDelEnd', function(e, s){
			var wpListsData = $(s.target).attr('data-wp-lists'), id = s.element.replace(/[^0-9]+/g, '');

			if ( wpListsData.indexOf(':trash=1') != -1 || wpListsData.indexOf(':spam=1') != -1 )
				$('#undo-' + id).fadeIn(300, function(){ $(this).show(); });
		});
};

commentReply = {
	cid : '',
	act : '',

	init : function() {
		var row = $('#replyrow');

		$('a.cancel', row).click(function() { return commentReply.revert(); });
		$('a.save', row).click(function() { return commentReply.send(); });
		$( 'input#author-name, input#author-email, input#author-url', row ).keypress( function( e ) {
			if ( e.which == 13 ) {
				commentReply.send();
				e.preventDefault();
				return false;
			}
		});

		// add events
		$('#the-comment-list .column-comment > p').dblclick(function(){
			commentReply.toggle($(this).parent());
		});

		$('#doaction, #doaction2, #post-query-submit').click(function(){
			if ( $('#the-comment-list #replyrow').length > 0 )
				commentReply.close();
		});

		this.comments_listing = $('#comments-form > input[name="comment_status"]').val() || '';

		/* $(listTable).bind('beforeChangePage', function(){
			commentReply.close();
		}); */
	},

	addEvents : function(r) {
		r.each(function() {
			$(this).find('.column-comment > p').dblclick(function(){
				commentReply.toggle($(this).parent());
			});
		});
	},

	toggle : function(el) {
		if ( 'none' !== $( el ).css( 'display' ) && ( $( '#replyrow' ).parent().is('#com-reply') || window.confirm( adminCommentsL10n.warnQuickEdit ) ) ) {
			$( el ).find( 'a.vim-q' ).click();
		}
	},

	revert : function() {

		if ( $('#the-comment-list #replyrow').length < 1 )
			return false;

		$('#replyrow').fadeOut('fast', function(){
			commentReply.close();
		});

		return false;
	},

	close : function() {
		var c, replyrow = $('#replyrow');

		// replyrow is not showing?
		if ( replyrow.parent().is('#com-reply') )
			return;

		if ( this.cid && this.act == 'edit-comment' ) {
			c = $('#comment-' + this.cid);
			c.fadeIn(300, function(){ c.show(); }).css('backgroundColor', '');
		}

		// reset the Quicktags buttons
		if ( typeof QTags != 'undefined' )
			QTags.closeAllTags('replycontent');

		$('#add-new-comment').css('display', '');

		replyrow.hide();
		$('#com-reply').append( replyrow );
		$('#replycontent').css('height', '').val('');
		$('#edithead input').val('');
		$('.error', replyrow).empty().hide();
		$( '.spinner', replyrow ).removeClass( 'is-active' );

		this.cid = '';
	},

	open : function(comment_id, post_id, action) {
		var editRow, rowData, act, replyButton, editHeight,
			t = this,
			c = $('#comment-' + comment_id),
			h = c.height(),
			colspanVal = 0;

		t.close();
		t.cid = comment_id;

		editRow = $('#replyrow');
		rowData = $('#inline-'+comment_id);
		action = action || 'replyto';
		act = 'edit' == action ? 'edit' : 'replyto';
		act = t.act = act + '-comment';
		colspanVal = $( '> th:visible, > td:visible', c ).length;

		// Make sure it's actually a table and there's a `colspan` value to apply.
		if ( editRow.hasClass( 'inline-edit-row' ) && 0 !== colspanVal ) {
			$( 'td', editRow ).attr( 'colspan', colspanVal );
		}

		$('#action', editRow).val(act);
		$('#comment_post_ID', editRow).val(post_id);
		$('#comment_ID', editRow).val(comment_id);

		if ( action == 'edit' ) {
			$( '#author-name', editRow ).val( $( 'div.author', rowData ).text() );
			$('#author-email', editRow).val( $('div.author-email', rowData).text() );
			$('#author-url', editRow).val( $('div.author-url', rowData).text() );
			$('#status', editRow).val( $('div.comment_status', rowData).text() );
			$('#replycontent', editRow).val( $('textarea.comment', rowData).val() );
			$( '#edithead, #editlegend, #savebtn', editRow ).show();
			$('#replyhead, #replybtn, #addhead, #addbtn', editRow).hide();

			if ( h > 120 ) {
				// Limit the maximum height when editing very long comments to make it more manageable.
				// The textarea is resizable in most browsers, so the user can adjust it if needed.
				editHeight = h > 500 ? 500 : h;
				$('#replycontent', editRow).css('height', editHeight + 'px');
			}

			c.after( editRow ).fadeOut('fast', function(){
				$('#replyrow').fadeIn(300, function(){ $(this).show(); });
			});
		} else if ( action == 'add' ) {
			$('#addhead, #addbtn', editRow).show();
			$( '#replyhead, #replybtn, #edithead, #editlegend, #savebtn', editRow ) .hide();
			$('#the-comment-list').prepend(editRow);
			$('#replyrow').fadeIn(300);
		} else {
			replyButton = $('#replybtn', editRow);
			$( '#edithead, #editlegend, #savebtn, #addhead, #addbtn', editRow ).hide();
			$('#replyhead, #replybtn', editRow).show();
			c.after(editRow);

			if ( c.hasClass('unapproved') ) {
				replyButton.text(adminCommentsL10n.replyApprove);
			} else {
				replyButton.text(adminCommentsL10n.reply);
			}

			$('#replyrow').fadeIn(300, function(){ $(this).show(); });
		}

		setTimeout(function() {
			var rtop, rbottom, scrollTop, vp, scrollBottom;

			rtop = $('#replyrow').offset().top;
			rbottom = rtop + $('#replyrow').height();
			scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			vp = document.documentElement.clientHeight || window.innerHeight || 0;
			scrollBottom = scrollTop + vp;

			if ( scrollBottom - 20 < rbottom )
				window.scroll(0, rbottom - vp + 35);
			else if ( rtop - 20 < scrollTop )
				window.scroll(0, rtop - 35);

			$('#replycontent').focus().keyup(function(e){
				if ( e.which == 27 )
					commentReply.revert(); // close on Escape
			});
		}, 600);

		return false;
	},

	send : function() {
		var post = {};

		$('#replysubmit .error').hide();
		$( '#replysubmit .spinner' ).addClass( 'is-active' );

		$('#replyrow input').not(':button').each(function() {
			var t = $(this);
			post[ t.attr('name') ] = t.val();
		});

		post.content = $('#replycontent').val();
		post.id = post.comment_post_ID;
		post.comments_listing = this.comments_listing;
		post.p = $('[name="p"]').val();

		if ( $('#comment-' + $('#comment_ID').val()).hasClass('unapproved') )
			post.approve_parent = 1;

		$.ajax({
			type : 'POST',
			url : ajaxurl,
			data : post,
			success : function(x) { commentReply.show(x); },
			error : function(r) { commentReply.error(r); }
		});

		return false;
	},

	show : function(xml) {
		var t = this, r, c, id, bg, pid;

		if ( typeof(xml) == 'string' ) {
			t.error({'responseText': xml});
			return false;
		}

		r = wpAjax.parseAjaxResponse(xml);
		if ( r.errors ) {
			t.error({'responseText': wpAjax.broken});
			return false;
		}

		t.revert();

		r = r.responses[0];
		id = '#comment-' + r.id;

		if ( 'edit-comment' == t.act )
			$(id).remove();

		if ( r.supplemental.parent_approved ) {
			pid = $('#comment-' + r.supplemental.parent_approved);
			updatePending( -1, r.supplemental.parent_post_id );

			if ( this.comments_listing == 'moderated' ) {
				pid.animate( { 'backgroundColor':'#CCEEBB' }, 400, function(){
					pid.fadeOut();
				});
				return;
			}
		}

		if ( r.supplemental.i18n_comments_text ) {
			if ( isDashboard ) {
				updateDashboardText( r.supplemental );
			} else {
				updateApproved( 1, r.supplemental.parent_post_id );
				updateCountText( 'span.all-count', 1 );
			}
		}

		c = $.trim(r.data); // Trim leading whitespaces
		$(c).hide();
		$('#replyrow').after(c);

		id = $(id);
		t.addEvents(id);
		bg = id.hasClass('unapproved') ? '#FFFFE0' : id.closest('.widefat, .postbox').css('backgroundColor');

		id.animate( { 'backgroundColor':'#CCEEBB' }, 300 )
			.animate( { 'backgroundColor': bg }, 300, function() {
				if ( pid && pid.length ) {
					pid.animate( { 'backgroundColor':'#CCEEBB' }, 300 )
						.animate( { 'backgroundColor': bg }, 300 )
						.removeClass('unapproved').addClass('approved')
						.find('div.comment_status').html('1');
				}
			});

	},

	error : function(r) {
		var er = r.statusText;

		$( '#replysubmit .spinner' ).removeClass( 'is-active' );

		if ( r.responseText )
			er = r.responseText.replace( /<.[^<>]*?>/g, '' );

		if ( er )
			$('#replysubmit .error').html(er).show();

	},

	addcomment: function(post_id) {
		var t = this;

		$('#add-new-comment').fadeOut(200, function(){
			t.open(0, post_id, 'add');
			$('table.comments-box').css('display', '');
			$('#no-comments').remove();
		});
	}
};

$(document).ready(function(){
	var make_hotkeys_redirect, edit_comment, toggle_all, make_bulk;

	setCommentsList();
	commentReply.init();

	$(document).on( 'click', 'span.delete a.delete', function( e ) {
		e.preventDefault();
	});

	if ( typeof $.table_hotkeys != 'undefined' ) {
		make_hotkeys_redirect = function(which) {
			return function() {
				var first_last, l;

				first_last = 'next' == which? 'first' : 'last';
				l = $('.tablenav-pages .'+which+'-page:not(.disabled)');
				if (l.length)
					window.location = l[0].href.replace(/\&hotkeys_highlight_(first|last)=1/g, '')+'&hotkeys_highlight_'+first_last+'=1';
			};
		};

		edit_comment = function(event, current_row) {
			window.location = $('span.edit a', current_row).attr('href');
		};

		toggle_all = function() {
			$('#cb-select-all-1').data( 'wp-toggle', 1 ).trigger( 'click' ).removeData( 'wp-toggle' );
		};

		make_bulk = function(value) {
			return function() {
				var scope = $('select[name="action"]');
				$('option[value="' + value + '"]', scope).prop('selected', true);
				$('#doaction').click();
			};
		};

		$.table_hotkeys(
			$('table.widefat'),
			[
				'a', 'u', 's', 'd', 'r', 'q', 'z',
				['e', edit_comment],
				['shift+x', toggle_all],
				['shift+a', make_bulk('approve')],
				['shift+s', make_bulk('spam')],
				['shift+d', make_bulk('delete')],
				['shift+t', make_bulk('trash')],
				['shift+z', make_bulk('untrash')],
				['shift+u', make_bulk('unapprove')]
			],
			{
				highlight_first: adminCommentsL10n.hotkeys_highlight_first,
				highlight_last: adminCommentsL10n.hotkeys_highlight_last,
				prev_page_link_cb: make_hotkeys_redirect('prev'),
				next_page_link_cb: make_hotkeys_redirect('next'),
				hotkeys_opts: {
					disableInInput: true,
					type: 'keypress',
					noDisable: '.check-column input[type="checkbox"]'
				},
				cycle_expr: '#the-comment-list tr',
				start_row_index: 0
			}
		);
	}

	// Quick Edit and Reply have an inline comment editor.
	$( '#the-comment-list' ).on( 'click', '.comment-inline', function (e) {
		e.preventDefault();
		var $el = $( this ),
			action = 'replyto';

		if ( 'undefined' !== typeof $el.data( 'action' ) ) {
			action = $el.data( 'action' );
		}

		commentReply.open( $el.data( 'commentId' ), $el.data( 'postId' ), action );
	} );
});

})(jQuery);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      !function(a,b){function c(a){var b,c={number:null,id_base:null};return b=a.match(/^(.+)-(\d+)$/),b?(c.id_base=b[1],c.number=parseInt(b[2],10)):c.id_base=a,c}function d(a){var b,d=c(a);return b="widget_"+d.id_base,d.number&&(b+="["+d.number+"]"),b}if(a&&a.customize){var e,f=a.customize;f.Widgets=f.Widgets||{},f.Widgets.savedWidgetIds={},f.Widgets.data=_wpCustomizeWidgetsSettings||{},e=f.Widgets.data.l10n,delete f.Widgets.data.l10n,f.Widgets.WidgetModel=Backbone.Model.extend({id:null,temp_id:null,classname:null,control_tpl:null,description:null,is_disabled:null,is_multi:null,multi_number:null,name:null,id_base:null,transport:null,params:[],width:null,height:null,search_matched:!0}),f.Widgets.WidgetCollection=Backbone.Collection.extend({model:f.Widgets.WidgetModel,doSearch:function(a){this.terms!==a&&(this.terms=a,this.terms.length>0&&this.search(this.terms),""===this.terms&&this.each(function(a){a.set("search_matched",!0)}))},search:function(a){var b,c;a=a.replace(/[-\/\\^$*+?.()|[\]{}]/g,"