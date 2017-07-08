,this.$("audio, video").each(function(a,b){var c=wp.media.view.MediaDetails.prepareSrc(b);new window.MediaElementPlayer(c,wp.media.mixin.mejsSettings)})}}),b.exports=d},{}],5:[function(a,b,c){var d,e=wp.media.view.Button,f=wp.media.view.DeleteSelectedButton;d=f.extend({initialize:function(){f.prototype.initialize.apply(this,arguments),this.listenTo(this.controller,"select:activate",this.selectActivate),this.listenTo(this.controller,"select:deactivate",this.selectDeactivate)},filterChange:function(a){this.canShow="trash"===a.get("status")},selectActivate:function(){this.toggleDisabled(),this.$el.toggleClass("hidden",!this.canShow)},selectDeactivate:function(){this.toggleDisabled(),this.$el.addClass("hidden")},render:function(){return e.prototype.render.apply(this,arguments),this.selectActivate(),this}}),b.exports=d},{}],6:[function(a,b,c){var d,e=wp.media.view.Button,f=wp.media.view.l10n;d=e.extend({initialize:function(){e.prototype.initialize.apply(this,arguments),this.options.filters&&this.listenTo(this.options.filters.model,"change",this.filterChange),this.listenTo(this.controller,"selection:toggle",this.toggleDisabled)},filterChange:function(a){"trash"===a.get("status")?this.model.set("text",f.untrashSelected):wp.media.view.settings.mediaTrash?this.model.set("text",f.trashSelected):this.model.set("text",f.deleteSelected)},toggleDisabled:function(){this.model.set("disabled",!this.controller.state().get("selection").length)},render:function(){return e.prototype.render.apply(this,arguments),this.controller.isModeActive("select")?this.$el.addClass("delete-selected-button"):this.$el.addClass("delete-selected-button hidden"),this.toggleDisabled(),this}}),b.exports=d},{}],7:[function(a,b,c){var d,e=wp.media.view.Button,f=wp.media.view.l10n;d=e.extend({initialize:function(){_.defaults(this.options,{size:""}),e.prototype.initialize.apply(this,arguments),this.listenTo(this.controller,"select:activate select:deactivate",this.toggleBulkEditHandler),this.listenTo(this.controller,"selection:action:done",this.back)},back:function(){this.controller.deactivateMode("select").activateMode("edit")},click:function(){e.prototype.click.apply(this,arguments),this.controller.isModeActive("select")?this.back():this.controller.deactivateMode("edit").activateMode("select")},render:function(){return e.prototype.render.apply(this,arguments),this.$el.addClass("select-mode-toggle-button"),this},toggleBulkEditHandler:function(){var a,b=this.controller.content.get().toolbar;a=b.$(".media-toolbar-secondary > *, .media-toolbar-primary > *"),this.controller.isModeActive("select")?(this.model.set({size:"large",text:f.cancelSelection}),a.not(".spinner, .media-button").hide(),this.$el.show(),b.$(".delete-selected-button").removeClass("hidden")):(this.model.set({size:"",text:f.bulkSelect}),this.controller.content.get().$el.removeClass("fixed"),b.$el.css("width",""),b.$(".delete-selected-button").addClass("hidden"),a.not(".media-button").show(),this.controller.state().get("selection").reset())}}),b.exports=d},{}],8:[function(a,b,c){var d,e=wp.media.View,f=wp.media.view.EditImage;d=f.extend({initialize:function(a){this.editor=window.imageEdit,this.frame=a.frame,this.controller=a.controller,e.prototype.initialize.apply(this,arguments)},back:function(){this.frame.content.mode("edit-metadata")},save:function(){this.model.fetch().done(_.bind(function(){this.frame.content.mode("edit-metadata")},this))}}),b.exports=d},{}],9:[function(a,b,c){var d,e=wp.media.view.Frame,f=wp.media.view.MediaFrame,g=jQuery;d=f.extend({className:"edit-attachment-frame",template:wp.template("edit-attachment-frame"),regions:["title","content"],events:{"click .left":"previousMediaItem","click .right":"nextMediaItem"},initialize:function(){e.prototype.initialize.apply(this,arguments),_.defaults(this.options,{modal:!0,state:"edit-attachment"}),this.controller=this.options.controller,this.gridRouter=this.controller.gridRouter,this.library=this.options.library,this.options.model&&(this.model=this.options.model),this.bindHandlers(),this.createStates(),this.createModal(),this.title.mode("default"),this.toggleNav()},bindHandlers:function(){this.on("title:create:default",this.createTitle,this),this.listenTo(this.model,"change:status destroy",this.close,this),this.on("content:create:edit-metadata",this.editMetadataMode,this),this.on("content:create:edit-image",this.editImageMode,this),this.on("content:render:edit-image",this.editImageModeRender,this),this.on("close",this.detach)},createModal:function(){this.options.modal&&(this.modal=new wp.media.view.Modal({controller:this,title:this.options.title}),this.modal.on("open",_.bind(function(){g("body").on("keydown.media-modal",_.bind(this.keyEvent,this))},this)),this.modal.on("close",_.bind(function(){this.modal.remove(),g("body").off("keydown.media-modal"),g('li.attachment[data-id="'+this.model.get("id")+'"]').focus(),this.resetRoute()},this)),this.modal.content(this),this.modal.open())},createStates:function(){this.states.add([new wp.media.controller.EditAttachmentMetadata({model:this.model})])},editMetadataMode:function(a){a.view=new wp.media.view.Attachment.Details.TwoColumn({controller:this,model:this.model}),a.view.views.set(".attachment-compat",new wp.media.view.AttachmentCompat({controller:this,model:this.model})),this.model&&this.gridRouter.navigate(this.gridRouter.baseUrl("?item="+this.model.id))},editImageMode:function(a){var b=new wp.media.controller.EditImage({model:this.model,frame:this});b._toolbar=function(){},b._router=function(){},b._menu=function(){},a.view=new wp.media.view.EditImage.Details({model:this.model,frame:this,controller:b})},editImageModeRender:function(a){a.on("ready",a.loadEditor)},toggleNav:function(){this.$(".left").toggleClass("disabled",!this.hasPrevious()),this.$(".right").toggleClass("disabled",!this.hasNext())},rerender:function(){"edit-metadata"!==this.content.mode()?this.content.mode("edit-metadata"):this.content.render(),this.toggleNav()},previousMediaItem:function(){return this.hasPrevious()?(this.model=this.library.at(this.getCurrentIndex()-1),this.rerender(),void this.$(".left").focus()):void this.$(".left").blur()},nextMediaItem:function(){return this.hasNext()?(this.model=this.library.at(this.getCurrentIndex()+1),this.rerender(),void this.$(".right").focus()):void this.$(".right").blur()},getCurrentIndex:function(){return this.library.indexOf(this.model)},hasNext:function(){return this.getCurrentIndex()+1<this.library.length},hasPrevious:function(){return this.getCurrentIndex()-1>-1},keyEvent:function(a){("INPUT"!==a.target.nodeName&&"TEXTAREA"!==a.target.nodeName||a.target.readOnly||a.target.disabled)&&(39===a.keyCode&&this.nextMediaItem(),37===a.keyCode&&this.previousMediaItem())},resetRoute:function(){this.gridRouter.navigate(this.gridRouter.baseUrl(""))}}),b.exports=d},{}],10:[function(a,b,c){var d,e=wp.media.view.MediaFrame,f=wp.media.controller.Library,g=Backbone.$;d=e.extend({initialize:function(){_.defaults(this.options,{title:"",modal:!1,selection:[],library:{},multiple:"add",state:"library",uploader:!0,mode:["grid","edit"]}),this.$body=g(document.body),this.$window=g(window),this.$adminBar=g("#wpadminbar"),this.$window.on("scroll resize",_.debounce(_.bind(this.fixPosition,this),15)),g(document).on("click",".page-title-action",_.bind(this.addNewClickHandler,this)),this.$el.addClass("wp-core-ui"),(wp.Uploader.limitExceeded||!wp.Uploader.browser.supported)&&(this.options.uploader=!1),this.options.uploader&&(this.uploader=new wp.media.view.UploaderWindow({controller:this,uploader:{dropzone:document.body,container:document.body}}).render(),this.uploader.ready(),g("body").append(this.uploader.el),this.options.uploader=!1),this.gridRouter=new wp.media.view.MediaFrame.Manage.Router,e.prototype.initialize.apply(this,arguments),this.$el.appendTo(this.options.container),this.createStates(),this.bindRegionModeHandlers(),this.render(),this.bindSearchHandler()},bindSearchHandler:function(){var a=this.$("#media-search-input"),b=this.options.container.data("search"),c=this.browserView.toolbar.get("search").$el,d=this.$(".view-list"),e=_.debounce(function(a){var b=g(a.currentTarget).val(),c="";b&&(c+="?search="+b),this.gridRouter.navigate(this.gridRouter.baseUrl(c))},1e3);a.on("input",_.bind(e,this)),c.val(b).trigger("input"),this.gridRouter.on("route:search",function(){var a=window.location.href;a.indexOf("mode=")>-1?a=a.replace(/mode=[^&]+/g,"mode=list"):a+=a.indexOf("?")>-1?"&mode=list":"?mode=list",a=a.replace("search=","s="),d.prop("href",a)})},createStates:function(){var a=this.options;this.options.states||this.states.add([new f({library:wp.media.query(a.library),multiple:a.multiple,title:a.title,content:"browse",toolbar:"select",contentUserSetting:!1,filterable:"all",autoSelect:!1})])},bindRegionModeHandlers:function(){this.on("content:create:browse",this.browseContent,this),this.on("edit:attachment",this.openEditAttachmentModal,this),this.on("select:activate",this.bindKeydown,this),this.on("select:deactivate",this.unbindKeydown,this)},handleKeydown:function(a){27===a.which&&(a.preventDefault(),this.deactivateMode("select").activateMode("edit"))},bindKeydown:function(){this.$body.on("keydown.select",_.bind(this.handleKeydown,this))},unbindKeydown:function(){this.$body.off("keydown.select")},fixPosition:function(){var a,b;this.isModeActive("select")&&(a=this.$(".attachments-browser"),b=a.find(".media-toolbar"),a.offset().top+16<this.$window.scrollTop()+this.$adminBar.height()?(a.addClass("fixed"),b.css("width",a.width()+"px")):(a.removeClass("fixed"),b.css("width","")))},addNewClickHandler:function(a){a.preventDefault(),this.trigger("toggle:upload:attachment")},openEditAttachmentModal:function(a){wp.media({frame:"edit-attachments",controller:this,library:this.state().get("library"),model:a})},browseContent:function(a){var b=this.state();this.browserView=a.view=new wp.media.view.AttachmentsBrowser({controller:this,collection:b.get("library"),selection:b.get("selection"),model:b,sortable:b.get("sortable"),search:b.get("searchable"),filters:b.get("filterable"),date:b.get("date"),display:b.get("displaySettings"),dragInfo:b.get("dragInfo"),sidebar:"errors",suggestedWidth:b.get("suggestedWidth"),suggestedHeight:b.get("suggestedHeight"),AttachmentView:b.get("AttachmentView"),scrollElement:document}),this.browserView.on("ready",_.bind(this.bindDeferred,this)),this.errors=wp.Uploader.errors,this.errors.on("add remove reset",this.sidebarVisibility,this)},sidebarVisibility:function(){this.browserView.$(".media-sidebar").toggle(!!this.errors.length)},bindDeferred:function(){this.browserView.dfd&&this.browserView.dfd.done(_.bind(this.startHistory,this))},startHistory:function(){window.history&&window.history.pushState&&Backbone.history.start({root:window._wpMediaGridSettings.adminUrl,pushState:!0})}}),b.exports=d},{}]},{},[2]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * wp.media.controller.EditAttachmentMetadata
 *
 * A state for editing an attachment's metadata.
 *
 * @class
 * @augments wp.media.controller.State
 * @augments Backbone.Model
 */
var l10n = wp.media.view.l10n,
	EditAttachmentMetadata;

EditAttachmentMetadata = wp.media.controller.State.extend({
	defaults: {
		id:      'edit-attachment',
		// Title string passed to the frame's title region view.
		title:   l10n.attachmentDetails,
		// Region mode defaults.
		content: 'edit-metadata',
		menu:    false,
		toolbar: false,
		router:  false
	}
});

module.exports = EditAttachmentMetadata;

},{}],2:[function(require,module,exports){
var media = wp.media;

media.controller.EditAttachmentMetadata = require( './controllers/edit-attachment-metadata.js' );
media.view.MediaFrame.Manage = require( './views/frame/manage.js' );
media.view.Attachment.Details.TwoColumn = require( './views/attachment/details-two-column.js' );
media.view.MediaFrame.Manage.Router = require( './routers/manage.js' );
media.view.EditImage.Details = require( './views/edit-image-details.js' );
media.view.MediaFrame.EditAttachments = require( './views/frame/edit-attachments.js' );
media.view.SelectModeToggleButton = require( './views/button/select-mode-toggle.js' );
media.view.DeleteSelectedButton = require( './views/button/delete-selected.js' );
media.view.DeleteSelectedPermanentlyButton = require( './views/button/delete-selected-permanently.js' );

},{"./controllers/edit-attachment-metadata.js":1,"./routers/manage.js":3,"./views/attachment/details-two-column.js":4,"./views/button/delete-selected-permanently.js":5,"./views/button/delete-selected.js":6,"./views/button/select-mode-toggle.js":7,"./views/edit-image-details.js":8,"./views/frame/edit-attachments.js":9,"./views/frame/manage.js":10}],3:[function(require,module,exports){
/**
 * wp.media.view.MediaFrame.Manage.Router
 *
 * A router for handling the browser history and application state.
 *
 * @class
 * @augments Backbone.Router
 */
var Router = Backbone.Router.extend({
	routes: {
		'upload.php?item=:slug':    'showItem',
		'upload.php?search=:query': 'search'
	},

	// Map routes against the page URL
	baseUrl: function( url ) {
		return 'upload.php' + url;
	},

	// Respond to the search route by filling the search field and trigggering the input event
	search: function( query ) {
		jQuery( '#media-search-input' ).val( query ).trigger( 'input' );
	},

	// Show the modal with a specific item
	showItem: function( query ) {
		var media = wp.media,
			library = media.frame.state().get('library'),
			item;

		// Trigger the media frame to open the correct item
		item = library.findWhere( { id: parseInt( query, 10 ) } );
		if ( item ) {
			media.frame.trigger( 'edit:attachment', item );
		} else {
			item = media.attachment( query );
			media.frame.listenTo( item, 'change', function( model ) {
				media.frame.stopListening( item );
				media.frame.trigger( 'edit:attachment', model );
			} );
			item.fetch();
		}
	}
});

module.exports = Router;

},{}],4:[function(require,module,exports){
/**
 * wp.media.view.Attachment.Details.TwoColumn
 *
 * A similar view to media.view.Attachment.Details
 * for use in the Edit Attachment modal.
 *
 * @class
 * @augments wp.media.view.Attachment.Details
 * @augments wp.media.view.Attachment
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var Details = wp.media.view.Attachment.Details,
	TwoColumn;

TwoColumn = Details.extend({
	template: wp.template( 'attachment-details-two-column' ),

	editAttachment: function( event ) {
		event.preventDefault();
		this.controller.content.mode( 'edit-image' );
	},

	/**
	 * Noop this from parent class, doesn't apply here.
	 */
	toggleSelectionHandler: function() {},

	render: function() {
		Details.prototype.render.apply( this, arguments );

		wp.media.mixin.removeAllPlayers();
		this.$( 'audio, video' ).each( function (i, elem) {
			var el = wp.media.view.MediaDetails.prepareSrc( elem );
			new window.MediaElementPlayer( el, wp.media.mixin.mejsSettings );
		} );
	}
});

module.exports = TwoColumn;

},{}],5:[function(require,module,exports){
/**
 * wp.media.view.DeleteSelectedPermanentlyButton
 *
 * When MEDIA_TRASH is true, a button that handles bulk Delete Permanently logic
 *
 * @class
 * @augments wp.media.view.DeleteSelectedButton
 * @augments wp.media.view.Button
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var Button = wp.media.view.Button,
	DeleteSelected = wp.media.view.DeleteSelectedButton,
	DeleteSelectedPermanently;

DeleteSelectedPermanently = DeleteSelected.extend({
	initialize: function() {
		DeleteSelected.prototype.initialize.apply( this, arguments );
		this.listenTo( this.controller, 'select:activate', this.selectActivate );
		this.listenTo( this.controller, 'select:deactivate', this.selectDeactivate );
	},

	filterChange: function( model ) {
		this.canShow = ( 'trash' === model.get( 'status' ) );
	},

	selectActivate: function() {
		this.toggleDisabled();
		this.$el.toggleClass( 'hidden', ! this.canShow );
	},

	selectDeactivate: function() {
		this.toggleDisabled();
		this.$el.addClass( 'hidden' );
	},

	render: function() {
		Button.prototype.render.apply( this, arguments );
		this.selectActivate();
		return this;
	}
});

module.exports = DeleteSelectedPermanently;

},{}],6:[function(require,module,exports){
/**
 * wp.media.view.DeleteSelectedButton
 *
 * A button that handles bulk Delete/Trash logic
 *
 * @class
 * @augments wp.media.view.Button
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var Button = wp.media.view.Button,
	l10n = wp.media.view.l10n,
	DeleteSelected;

DeleteSelected = Button.extend({
	initialize: function() {
		Button.prototype.initialize.apply( this, arguments );
		if ( this.options.filters ) {
			this.listenTo( this.options.filters.model, 'change', this.filterChange );
		}
		this.listenTo( this.controller, 'selection:toggle', this.toggleDisabled );
	},

	filterChange: function( model ) {
		if ( 'trash' === model.get( 'status' ) ) {
			this.model.set( 'text', l10n.untrashSelected );
		} else if ( wp.media.view.settings.mediaTrash ) {
			this.model.set( 'text', l10n.trashSelected );
		} else {
			this.model.set( 'text', l10n.deleteSelected );
		}
	},

	toggleDisabled: function() {
		this.model.set( 'disabled', ! this.controller.state().get( 'selection' ).length );
	},

	render: function() {
		Button.prototype.render.apply( this, arguments );
		if ( this.controller.isModeActive( 'select' ) ) {
			this.$el.addClass( 'delete-selected-button' );
		} else {
			this.$el.addClass( 'delete-selected-button hidden' );
		}
		this.toggleDisabled();
		return this;
	}
});

module.exports = DeleteSelected;

},{}],7:[function(require,module,exports){
/**
 * wp.media.view.SelectModeToggleButton
 *
 * @class
 * @augments wp.media.view.Button
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var Button = wp.media.view.Button,
	l10n = wp.media.view.l10n,
	SelectModeToggle;

SelectModeToggle = Button.extend({
	initialize: function() {
		_.defaults( this.options, {
			size : ''
		} );

		Button.prototype.initialize.apply( this, arguments );
		this.listenTo( this.controller, 'select:activate select:deactivate', this.toggleBulkEditHandler );
		this.listenTo( this.controller, 'selection:action:done', this.back );
	},

	back: function () {
		this.controller.deactivateMode( 'select' ).activateMode( 'edit' );
	},

	click: function() {
		Button.prototype.click.apply( this, arguments );
		if ( this.controller.isModeActive( 'select' ) ) {
			this.back();
		} else {
			this.controller.deactivateMode( 'edit' ).activateMode( 'select' );
		}
	},

	render: function() {
		Button.prototype.render.apply( this, arguments );
		this.$el.addClass( 'select-mode-toggle-button' );
		return this;
	},

	toggleBulkEditHandler: function() {
		var toolbar = this.controller.content.get().toolbar, children;

		children = toolbar.$( '.media-toolbar-secondary > *, .media-toolbar-primary > *' );

		// TODO: the Frame should be doing all of this.
		if ( this.controller.isModeActive( 'select' ) ) {
			this.model.set( {
				size: 'large',
				text: l10n.cancelSelection
			} );
			children.not( '.spinner, .media-button' ).hide();
			this.$el.show();
			toolbar.$( '.delete-selected-button' ).removeClass( 'hidden' );
		} else {
			this.model.set( {
				size: '',
				text: l10n.bulkSelect
			} );
			this.controller.content.get().$el.removeClass( 'fixed' );
			toolbar.$el.css( 'width', '' );
			toolbar.$( '.delete-selected-button' ).addClass( 'hidden' );
			children.not( '.media-button' ).show();
			this.controller.state().get( 'selection' ).reset();
		}
	}
});

module.exports = SelectModeToggle;

},{}],8:[function(require,module,exports){
/**
 * wp.media.view.EditImage.Details
 *
 * @class
 * @augments wp.media.view.EditImage
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var View = wp.media.View,
	EditImage = wp.media.view.EditImage,
	Details;

Details = EditImage.extend({
	initialize: function( options ) {
		this.editor = window.imageEdit;
		this.frame = options.frame;
		this.controller = options.controller;
		View.prototype.initialize.apply( this, arguments );
	},

	back: function() {
		this.frame.content.mode( 'edit-metadata' );
	},

	save: function() {
		this.model.fetch().done( _.bind( function() {
			this.frame.content.mode( 'edit-metadata' );
		}, this ) );
	}
});

module.exports = Details;

},{}],9:[function(require,module,exports){
/**
 * wp.media.view.MediaFrame.EditAttachments
 *
 * A frame for editing the details of a specific media item.
 *
 * Opens in a modal by default.
 *
 * Requires an attachment model to be passed in the options hash under `model`.
 *
 * @class
 * @augments wp.media.view.Frame
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 * @mixes wp.media.controller.StateMachine
 */
var Frame = wp.media.view.Frame,
	MediaFrame = wp.media.view.MediaFrame,

	$ = jQuery,
	EditAttachments;

EditAttachments = MediaFrame.extend({

	className: 'edit-attachment-frame',
	template:  wp.template( 'edit-attachment-frame' ),
	regions:   [ 'title', 'content' ],

	events: {
		'click .left':  'previousMediaItem',
		'click .right': 'nextMediaItem'
	},

	initialize: function() {
		Frame.prototype.initialize.apply( this, arguments );

		_.defaults( this.options, {
			modal: true,
			state: 'edit-attachment'
		});

		this.controller = this.options.controller;
		this.gridRouter = this.controller.gridRouter;
		this.library = this.options.library;

		if ( this.options.model ) {
			this.model = this.options.model;
		}

		this.bindHandlers();
		this.createStates();
		this.createModal();

		this.title.mode( 'default' );
		this.toggleNav();
	},

	bindHandlers: function() {
		// Bind default title creation.
		this.on( 'title:create:default', this.createTitle, this );

		// Close the modal if the attachment is deleted.
		this.listenTo( this.model, 'change:status destroy', this.close, this );

		this.on( 'content:create:edit-metadata', this.editMetadataMode, this );
		this.on( 'content:create:edit-image', this.editImageMode, this );
		this.on( 'content:render:edit-image', this.editImageModeRender, this );
		this.on( 'close', this.detach );
	},

	createModal: function() {
		// Initialize modal container view.
		if ( this.options.modal ) {
			this.modal = new wp.media.view.Modal({
				controller: this,
				title:      this.options.title
			});

			this.modal.on( 'open', _.bind( function () {
				$( 'body' ).on( 'keydown.media-modal', _.bind( this.keyEvent, this ) );
			}, this ) );

			// Completely destroy the modal DOM element when closing it.
			this.modal.on( 'close', _.bind( function() {
				this.modal.remove();
				$( 'body' ).off( 'keydown.media-modal' ); /* remove the keydown event */
				// Restore the original focus item if possible
				$( 'li.attachment[data-id="' + this.model.get( 'id' ) +'"]' ).focus();
				this.resetRoute();
			}, this ) );

			// Set this frame as the modal's content.
			this.modal.content( this );
			this.modal.open();
		}
	},

	/**
	 * Add the default states to the frame.
	 */
	createStates: function() {
		this.states.add([
			new wp.media.controller.EditAttachmentMetadata( { model: this.model } )
		]);
	},

	/**
	 * Content region rendering callback for the `edit-metadata` mode.
	 *
	 * @param {Object} contentRegion Basic object with a `view` property, which
	 *                               should be set with the proper region view.
	 */
	editMetadataMode: function( contentRegion ) {
		contentRegion.view = new wp.media.view.Attachment.Details.TwoColumn({
			controller: this,
			model:      this.model
		});

		/**
		 * Attach a subview to display fields added via the
		 * `attachment_fields_to_edit` filter.
		 */
		contentRegion.view.views.set( '.attachment-compat', new wp.media.view.AttachmentCompat({
			controller: this,
			model:      this.model
		}) );

		// Update browser url when navigating media details
		if ( this.model ) {
			this.gridRouter.navigate( this.gridRouter.baseUrl( '?item=' + this.model.id ) );
		}
	},

	/**
	 * Render the EditImage view into the frame's content region.
	 *
	 * @param {Object} contentRegion Basic object with a `view` property, which
	 *                               should be set with the proper region view.
	 */
	editImageMode: function( contentRegion ) {
		var editImageController = new wp.media.controller.EditImage( {
			model: this.model,
			frame: this
		} );
		// Noop some methods.
		editImageController._toolbar = function() {};
		editImageController._router = function() {};
		editImageController._menu = function() {};

		contentRegion.view = new wp.media.view.EditImage.Details( {
			model: this.model,
			frame: this,
			controller: editImageController
		} );
	},

	editImageModeRender: function( view ) {
		view.on( 'ready', view.loadEditor );
	},

	toggleNav: function() {
		this.$('.left').toggleClass( 'disabled', ! this.hasPrevious() );
		this.$('.right').toggleClass( 'disabled', ! this.hasNext() );
	},

	/**
	 * Rerender the view.
	 */
	rerender: function() {
		// Only rerender the `content` region.
		if ( this.content.mode() !== 'edit-metadata' ) {
			this.content.mode( 'edit-metadata' );
		} else {
			this.content.render();
		}

		this.toggleNav();
	},

	/**
	 * Click handler to switch to the previous media item.
	 */
	previousMediaItem: function() {
		if ( ! this.hasPrevious() ) {
			this.$( '.left' ).blur();
			return;
		}
		this.model = this.library.at( this.getCurrentIndex() - 1 );
		this.rerender();
		this.$( '.left' ).focus();
	},

	/**
	 * Click handler to switch to the next media item.
	 */
	nextMediaItem: function() {
		if ( ! this.hasNext() ) {
			this.$( '.right' ).blur();
			return;
		}
		this.model = this.library.at( this.getCurrentIndex() + 1 );
		this.rerender();
		this.$( '.right' ).focus();
	},

	getCurrentIndex: function() {
		return this.library.indexOf( this.model );
	},

	hasNext: function() {
		return ( this.getCurrentIndex() + 1 ) < this.library.length;
	},

	hasPrevious: function() {
		return ( this.getCurrentIndex() - 1 ) > -1;
	},
	/**
	 * Respond to the keyboard events: right arrow, left arrow, except when
	 * focus is in a textarea or input field.
	 */
	keyEvent: function( event ) {
		if ( ( 'INPUT' === event.target.nodeName || 'TEXTAREA' === event.target.nodeName ) && ! ( event.target.readOnly || event.target.disabled ) ) {
			return;
		}

		// The right arrow key
		if ( 39 === event.keyCode ) {
			this.nextMediaItem();
		}
		// The left arrow key
		if ( 37 === event.keyCode ) {
			this.previousMediaItem();
		}
	},

	resetRoute: function() {
		this.gridRouter.navigate( this.gridRouter.baseUrl( '' ) );
	}
});

module.exports = EditAttachments;

},{}],10:[function(require,module,exports){
/**
 * wp.media.view.MediaFrame.Manage
 *
 * A generic management frame workflow.
 *
 * Used in the media grid view.
 *
 * @class
 * @augments wp.media.view.MediaFrame
 * @augments wp.media.view.Frame
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 * @mixes wp.media.controller.StateMachine
 */
var MediaFrame = wp.media.view.MediaFrame,
	Library = wp.media.controller.Library,

	$ = Backbone.$,
	Manage;

Manage = MediaFrame.extend({
	/**
	 * @global wp.Uploader
	 */
	initialize: function() {
		_.defaults( this.options, {
			title:     '',
			modal:     false,
			selection: [],
			library:   {}, // Options hash for the query to the media library.
			multiple:  'add',
			state:     'library',
			uploader:  true,
			mode:      [ 'grid', 'edit' ]
		});

		this.$body = $( document.body );
		this.$window = $( window );
		this.$adminBar = $( '#wpadminbar' );
		this.$window.on( 'scroll resize', _.debounce( _.bind( this.fixPosition, this ), 15 ) );
		$( document ).on( 'click', '.page-title-action', _.bind( this.addNewClickHandler, this ) );

		// Ensure core and media grid view UI is enabled.
		this.$el.addClass('wp-core-ui');

		// Force the uploader off if the upload limit has been exceeded or
		// if the browser isn't supported.
		if ( wp.Uploader.limitExceeded || ! wp.Uploader.browser.supported ) {
			this.options.uploader = false;
		}

		// Initialize a window-wide uploader.
		if ( this.options.uploader ) {
			this.uploader = new wp.media.view.UploaderWindow({
				controller: this,
				uploader: {
					dropzone:  document.body,
					container: document.body
				}
			}).render();
			this.uploader.ready();
			$('body').append( this.uploader.el );

			this.options.uploader = false;
		}

		this.gridRouter = new wp.media.view.MediaFrame.Manage.Router();

		// Call 'initialize' directly on the parent class.
		MediaFrame.prototype.initialize.apply( this, arguments );

		// Append the frame view directly the supplied container.
		this.$el.appendTo( this.options.container );

		this.createStates();
		this.bindRegionModeHandlers();
		this.render();
		this.bindSearchHandler();
	},

	bindSearchHandler: function() {
		var search = this.$( '#media-search-input' ),
			currentSearch = this.options.container.data( 'search' ),
			searchView = this.browserView.toolbar.get( 'search' ).$el,
			listMode = this.$( '.view-list' ),

			input  = _.debounce( function (e) {
				var val = $( e.currentTarget ).val(),
					url = '';

				if ( val ) {
					url += '?search=' + val;
				}
				this.gridRouter.navigate( this.gridRouter.baseUrl( url ) );
			}, 1000 );

		// Update the URL when entering search string (at most once per second)
		search.on( 'input', _.bind( input, this ) );
		searchView.val( currentSearch ).trigger( 'input' );

		this.gridRouter.on( 'route:search', function () {
			var href = window.location.href;
			if ( href.indexOf( 'mode=' ) > -1 ) {
				href = href.replace( /mode=[^&]+/g, 'mode=list' );
			} else {
				href += href.indexOf( '?' ) > -1 ? '&mode=list' : '?mode=list';
			}
			href = href.replace( 'search=', 's=' );
			listMode.prop( 'href', href );
		} );
	},

	/**
	 * Create the default states for the frame.
	 */
	createStates: function() {
		var options = this.options;

		if ( this.options.states ) {
			return;
		}

		// Add the default states.
		this.states.add([
			new Library({
				library:            wp.media.query( options.library ),
				multiple:           options.multiple,
				title:              options.title,
				content:            'browse',
				toolbar:            'select',
				contentUserSetting: false,
				filterable:         'all',
				autoSelect:         false
			})
		]);
	},

	/**
	 * Bind region mode activation events to proper handlers.
	 */
	bindRegionModeHandlers: function() {
		this.on( 'content:create:browse', this.browseContent, this );

		// Handle a frame-level event for editing an attachment.
		this.on( 'edit:attachment', this.openEditAttachmentModal, this );

		this.on( 'select:activate', this.bindKeydown, this );
		this.on( 'select:deactivate', this.unbindKeydown, this );
	},

	handleKeydown: function( e ) {
		if ( 27 === e.which ) {
			e.preventDefault();
			this.deactivateMode( 'select' ).activateMode( 'edit' );
		}
	},

	bindKeydown: function() {
		this.$body.on( 'keydown.select', _.bind( this.handleKeydown, this ) );
	},

	unbindKeydown: function() {
		this.$body.off( 'keydown.select' );
	},

	fixPosition: function() {
		var $browser, $toolbar;
		if ( ! this.isModeActive( 'select' ) ) {
			return;
		}

		$browser = this.$('.attachments-browser');
		$toolbar = $browser.find('.media-toolbar');

		// Offset doesn't appear to take top margin into account, hence +16
		if ( ( $browser.offset().top + 16 ) < this.$window.scrollTop() + this.$adminBar.height() ) {
			$browser.addClass( 'fixed' );
			$toolbar.css('width', $browser.width() + 'px');
		} else {
			$browser.removeClass( 'fixed' );
			$toolbar.css('width', '');
		}
	},

	/**
	 * Click handler for the `Add New` button.
	 */
	addNewClickHandler: function( event ) {
		event.preventDefault();
		this.trigger( 'toggle:upload:attachment' );
	},

	/**
	 * Open the Edit Attachment modal.
	 */
	openEditAttachmentModal: function( model ) {
		// Create a new EditAttachment frame, passing along the library and the attachment model.
		wp.media( {
			frame:       'edit-attachments',
			controller:  this,
			library:     this.state().get('library'),
			model:       model
		} );
	},

	/**
	 * Create an attachments browser view within the content region.
	 *
	 * @param {Object} contentRegion Basic object with a `view` property, which
	 *                               should be set with the proper region view.
	 * @this wp.media.controller.Region
	 */
	browseContent: function( contentRegion ) {
		var state = this.state();

		// Browse our library of attachments.
		this.browserView = contentRegion.view = new wp.media.view.AttachmentsBrowser({
			controller: this,
			collection: state.get('library'),
			selection:  state.get('selection'),
			model:      state,
			sortable:   state.get('sortable'),
			search:     state.get('searchable'),
			filters:    state.get('filterable'),
			date:       state.get('date'),
			display:    state.get('displaySettings'),
			dragInfo:   state.get('dragInfo'),
			sidebar:    'errors',

			suggestedWidth:  state.get('suggestedWidth'),
			suggestedHeight: state.get('suggestedHeight'),

			AttachmentView: state.get('AttachmentView'),

			scrollElement: document
		});
		this.browserView.on( 'ready', _.bind( this.bindDeferred, this ) );

		this.errors = wp.Uploader.errors;
		this.errors.on( 'add remove reset', this.sidebarVisibility, this );
	},

	sidebarVisibility: function() {
		this.browserView.$( '.media-sidebar' ).toggle( !! this.errors.length );
	},

	bindDeferred: function() {
		if ( ! this.browserView.dfd ) {
			return;
		}
		this.browserView.dfd.done( _.bind( this.startHistory, this ) );
	},

	startHistory: function() {
		// Verify pushState support and activate
		if ( window.history && window.history.pushState ) {
			Backbone.history.start( {
				root: window._wpMediaGridSettings.adminUrl,
				pushState: true
			} );
		}
	}
});

module.exports = Manage;

},{}]},{},[2]);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              !function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);var j=new Error("Cannot find module '"+g+"'");throw j.code="MODULE_NOT_FOUND",j}var k=c[g]={exports:{}};b[g][0].call(k.exports,function(a){var c=b[g][1][a];return e(c?c:a)},k,k.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){var d=wp.media,e=window._wpmejsSettings||{},f=window._wpMediaViewsL10n||{};wp.media.mixin={mejsSettings:e,removeAllPlayers:function(){var a;if(window.mejs&&window.mejs.players)for(a in window.mejs.players)window.mejs.players[a].pause(),this.removePlayer(window.mejs.players[a])},removePlayer:function(a){var b,c;if(a.options){for(b in a.options.features)if(c=a.options.features[b],a["clean"+c])try{a["clean"+c](a)}catch(d){}a.isDynamic||a.$node.remove(),"native"!==a.media.pluginType&&a.$media.remove(),delete window.mejs.players[a.id],a.container.remove(),a.globalUnbind(),delete a.node.player}},unsetPlayers:function(){this.players&&this.players.length&&(_.each(this.players,function(a){a.pause(),wp.media.mixin.removePlayer(a)}),this.players=[])}},wp.media.playlist=new wp.media.collection({tag:"playlist",editTitle:f.editPlaylistTitle,defaults:{id:wp.media.view.settings.post.id,style:"light",tracklist:!0,tracknumbers:!0,images:!0,artists:!0,type:"audio"}}),wp.media.audio={coerce:wp.media.coerce,defaults:{id:wp.media.view.settings.post.id,src:"",loop:!1,autoplay:!1,preload:"none",width:400},edit:function(a){var b,c=wp.shortcode.next("audio",a).shortcode;return b=wp.media({frame:"audio",state:"audio-details",metadata:_.defaults(c.attrs.named,this.defaults)})},shortcode:function(a){var b;return _.each(this.defaults,function(b,c){a[c]=this.coerce(a,c),b===a[c]&&delete a[c]},this),b=a.content,delete a.content,new wp.shortcode({tag:"audio",attrs:a,content:b})}},wp.media.video={coerce:wp.media.coerce,defaults:{id:wp.media.view.settings.post.id,src:"",poster:"",loop:!1,autoplay:!1,preload:"metadata",content:"",width:640,height:360},edit:function(a){var b,c,d=wp.shortcode.next("video",a).shortcode;return c=d.attrs.named,c.content=d.content,b=wp.media({frame:"video",state:"video-details",metadata:_.defaults(c,this.defaults)})},shortcode:function(a){var b;return _.each(this.defaults,function(b,c){a[c]=this.coerce(a,c),b===a[c]&&delete a[c]},this),b=a.content,delete a.content,new wp.shortcode({tag:"video",attrs:a,content:b})}},d.model.PostMedia=a("./models/post-media.js"),d.controller.AudioDetails=a("./controllers/audio-details.js"),d.controller.VideoDetails=a("./controllers/video-details.js"),d.view.MediaFrame.MediaDetails=a("./views/frame/media-details.js"),d.view.MediaFrame.AudioDetails=a("./views/frame/audio-details.js"),d.view.MediaFrame.VideoDetails=a("./views/frame/video-details.js"),d.view.MediaDetails=a("./views/media-details.js"),d.view.AudioDetails=a("./views/audio-details.js"),d.view.VideoDetails=a("./views/video-details.js")},{"./controllers/audio-details.js":2,"./controllers/video-details.js":3,"./models/post-media.js":4,"./views/audio-details.js":5,"./views/frame/audio-details.js":6,"./views/frame/media-details.js":7,"./views/frame/video-details.js":8,"./views/media-details.js":9,"./views/video-details.js":10}],2:[function(a,b,c){var d,e=wp.media.controller.State,f=wp.media.view.l10n;d=e.extend({defaults:{id:"audio-details",toolbar:"audio-details",title:f.audioDetailsTitle,content:"audio-details",menu:"audio-details",router:!1,priority:60},initialize:function(a){this.media=a.media,e.prototype.initialize.apply(this,arguments)}}),b.exports=d},{}],3:[function(a,b,c){var d,e=wp.media.controller.State,f=wp.media.view.l10n;d=e.extend({defaults:{id:"video-details",toolbar:"video-details",title:f.videoDetailsTitle,content:"video-details",menu:"video-details",router:!1,priority:60},initialize:function(a){this.media=a.media,e.prototype.initialize.apply(this,arguments)}}),b.exports=d},{}],4:[function(a,b,c){var d=Backbone.Model.extend({initialize:function(){this.attachment=!1},setSource:function(a){this.attachment=a,this.extension=a.get("filename").split(".").pop(),this.get("src")&&this.extension===this.get("src").split(".").pop()&&this.unset("src"),_.contains(wp.media.view.settings.embedExts,this.extension)?this.set(this.extension,this.attachment.get("url")):this.unset(this.extension)},changeAttachment:function(a){this.setSource(a),this.unset("src"),_.each(_.without(wp.media.view.settings.embedExts,this.extension),function(a){this.unset(a)},this)}});b.exports=d},{}],5:[function(a,b,c){var d,e=wp.media.view.MediaDetails;d=e.extend({className:"audio-details",template:wp.template("audio-details"),setMedia:function(){var a=this.$(".wp-audio-shortcode");return a.find("source").length?(a.is(":hidden")&&a.show(),this.media=e.prepareSrc(a.get(0))):(a.hide(),this.media=!1),this}}),b.exports=d},{}],6:[function(a,b,c){var d,e=wp.media.view.MediaFrame.MediaDetails,f=wp.media.controller.MediaLibrary,g=wp.media.view.l10n;d=e.extend({defaults:{id:"audio",url:"",menu:"audio-details",content:"audio-details",toolbar:"audio-details",type:"link",title:g.audioDetailsTitle,priority:120},initialize:function(a){a.DetailsView=wp.media.view.AudioDetails,a.cancelText=g.audioDetailsCancel,a.addText=g.audioAddSourceTitle,e.prototype.initialize.call(this,a)},bindHandlers:function(){e.prototype.bindHandlers.apply(this,arguments),this.on("toolbar:render:replace-audio",this.renderReplaceToolbar,this),this.on("toolbar:render:add-audio-source",this.renderAddSourceToolbar,this)},createStates:function(){this.states.add([new wp.media.controller.AudioDetails({media:this.media}),new f({type:"audio",id:"replace-audio",title:g.audioReplaceTitle,toolbar:"replace-audio",media:this.media,menu:"audio-details"}),new f({type:"audio",id:"add-audio-source",title:g.audioAddSourceTitle,toolbar:"add-audio-source",media:this.media,menu:!1})])}}),b.exports=d},{}],7:[function(a,b,c){var d,e=wp.media.view.MediaFrame.Select,f=wp.media.view.l10n;d=e.extend({defaults:{id:"media",url:"",menu:"media-details",content:"media-details",toolbar:"media-details",type:"link",priority:120},initialize:function(a){this.DetailsView=a.DetailsView,this.cancelText=a.cancelText,this.addText=a.addText,this.media=new wp.media.model.PostMedia(a.metadata),this.options.selection=new wp.media.model.Selection(this.media.attachment,{multiple:!1}),e.prototype.initialize.apply(this,arguments)},bindHandlers:function(){var a=this.defaults.menu;e.prototype.bindHandlers.apply(this,arguments),this.on("menu:create:"+a,this.createMenu,this),this.on("content:render:"+a,this.renderDetailsContent,this),this.on("menu:render:"+a,this.renderMenu,this),this.on("toolbar:render:"+a,this.renderDetailsToolbar,this)},renderDetailsContent:function(){var a=new this.DetailsView({controller:this,model:this.state().media,attachment:this.state().media.attachment}).render();this.content.set(a)},renderMenu:function(a){var b=this.lastState(),c=b&&b.id,d=this;a.set({cancel:{text:this.cancelText,priority:20,click:function(){c?d.setState(c):d.close()}},separateCancel:new wp.media.View({className:"separator",priority:40})})},setPrimaryButton:function(a,b){this.toolbar.set(new wp.media.view.Toolbar({controller:this,items:{button:{style:"primary",text:a,priority:80,click:function(){var a=this.controller;b.call(this,a,a.state()),a.setState(a.options.state),a.reset()}}}}))},renderDetailsToolbar:function(){this.setPrimaryButton(f.update,function(a,b){a.close(),b.trigger("update",a.media.toJSON())})},renderReplaceToolbar:function(){this.setPrimaryButton(f.replace,function(a,b){var c=b.get("selection").single();a.media.changeAttachment(c),b.trigger("replace",a.media.toJSON())})},renderAddSourceToolbar:function(){this.setPrimaryButton(this.addText,function(a,b){var c=b.get("selection").single();a.media.setSource(c),b.trigger("add-source",a.media.toJSON())})}}),b.exports=d},{}],8:[function(a,b,c){var d,e=wp.media.view.MediaFrame.MediaDetails,f=wp.media.controller.MediaLibrary,g=wp.media.view.l10n;d=e.extend({defaults:{id:"video",url:"",menu:"video-details",content:"video-details",toolbar:"video-details",type:"link",title:g.videoDetailsTitle,priority:120},initialize:function(a){a.DetailsView=wp.media.view.VideoDetails,a.cancelText=g.videoDetailsCancel,a.addText=g.videoAddSourceTitle,e.prototype.initialize.call(this,a)},bindHandlers:function(){e.prototype.bindHandlers.apply(this,arguments),this.on("toolbar:render:replace-video",this.renderReplaceToolbar,this),this.on("toolbar:render:add-video-source",this.renderAddSourceToolbar,this),this.on("toolbar:render:select-poster-image",this.renderSelectPosterImageToolbar,this),this.on("toolbar:render:add-track",this.renderAddTrackToolbar,this)},createStates:function(){this.states.add([new wp.media.controller.VideoDetails({media:this.media}),new f({type:"video",id:"replace-video",title:g.videoReplaceTitle,toolbar:"replace-video",media:this.media,menu:"video-details"}),new f({type:"video",id:"add-video-source",title:g.videoAddSourceTitle,toolbar:"add-video-source",media:this.media,menu:!1}),new f({type:"image",id:"select-poster-image",title:g.videoSelectPosterImageTitle,toolbar:"select-poster-image",media:this.media,menu:"video-details"}),new f({type:"text",id:"add-track",title:g.videoAddTrackTitle,toolbar:"add-track",media:this.media,menu:"video-details"})])},renderSelectPosterImageToolbar:function(){this.setPrimaryButton(g.videoSelectPosterImageTitle,function(a,b){var c=[],d=b.get("selection").single();a.media.set("poster",d.get("url")),b.trigger("set-poster-image",a.media.toJSON()),_.each(wp.media.view.settings.embedExts,function(b){a.media.get(b)&&c.push(a.media.get(b))}),wp.ajax.send("set-attachment-thumbnail",{data:{urls:c,thumbnail_id:d.get("id")}})})},renderAddTrackToolbar:function(){this.setPrimaryButton(g.videoAddTrackTitle,function(a,b){var c=b.get("selection").single(),d=a.media.get("content");-1===d.indexOf(c.get("url"))&&(d+=['<track srclang="en" label="English" kind="subtitles" src="',c.get("url"),'" />'].join(""),a.media.set("content",d)),b.trigger("add-track",a.media.toJSON())})}}),b.exports=d},{}],9:[function(a,b,c){var d,e=wp.media.view.Settings.AttachmentDisplay,f=jQuery;d=e.extend({initialize:function(){_.bindAll(this,"success"),this.players=[],this.listenTo(this.controller,"close",wp.media.mixin.unsetPlayers),this.on("ready",this.setPlayer),this.on("media:setting:remove",wp.media.mixin.unsetPlayers,this),this.on("media:setting:remove",this.render),this.on("media:setting:remove",this.setPlayer),this.events=_.extend(this.events,{"click .remove-setting":"removeSetting","change .content-track":"setTracks","click .remove-track":"setTracks","click .add-media-source":"addSource"}),e.prototype.initialize.apply(this,arguments)},prepare:function(){return _.defaults({model:this.model.toJSON()},this.options)},removeSetting:function(a){var b,c=f(a.currentTarget).parent();b=c.find("input").data("setting"),b&&(this.model.unset(b),this.trigger("media:setting:remove",this)),c.remove()},setTracks:function(){var a="";_.each(this.$(".content-track"),function(b){a+=f(b).val()}),this.model.set("content",a),this.trigger("media:setting:remove",this)},addSource:function(a){this.controller.lastMime=f(a.currentTarget).data("mime"),this.controller.setState("add-"+this.controller.defaults.id+"-source")},loadPlayer:function(){this.players.push(new MediaElementPlayer(this.media,this.settings)),this.scriptXhr=!1},setPlayer:function(){var a;this.players.length||!this.media||this.scriptXhr||(this.model.get("src").indexOf("vimeo")>-1&&!("Froogaloop"in window)?(a=wp.media.mixin.mejsSettings,this.scriptXhr=f.getScript(a.pluginPath+"froogaloop.min.js",_.bind(this.loadPlayer,this))):this.loadPlayer())},setMedia:function(){return this},success:function(a){var b=a.attributes.autoplay&&"false"!==a.attributes.autoplay;"flash"===a.pluginType&&b&&a.addEventListener("canplay",function(){a.play()},!1),this.mejs=a},render:function(){return e.prototype.render.apply(this,arguments),setTimeout(_.bind(function(){this.resetFocus()},this),10),this.settings=_.defaults({success:this.success},wp.media.mixin.mejsSettings),this.setMedia()},resetFocus:function(){this.$(".embed-media-settings").scrollTop(0)}},{instances:0,prepareSrc:function(a){var b=d.instances++;return _.each(f(a).find("source"),function(a){a.src=[a.src,a.src.indexOf("?")>-1?"&":"?","_=",b].join("")}),a}}),b.exports=d},{}],10:[function(a,b,c){var d,e=wp.media.view.MediaDetails;d=e.extend({className:"video-details",template:wp.template("video-details"),setMedia:function(){var a=this.$(".wp-video-shortcode");return a.find("source").length?(a.is(":hidden")&&a.show(),a.hasClass("youtube-video")||a.hasClass("vimeo-video")?this.media=a.get(0):this.media=e.prepareSrc(a.get(0))):(a.hide(),this.media=!1),this}}),b.exports=d},{}]},{},[1]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var media = wp.media,
	baseSettings = window._wpmejsSettings || {},
	l10n = window._wpMediaViewsL10n || {};

/**
 * @mixin
 */
wp.media.mixin = {
	mejsSettings: baseSettings,

	removeAllPlayers: function() {
		var p;

		if ( window.mejs && window.mejs.players ) {
			for ( p in window.mejs.players ) {
				window.mejs.players[p].pause();
				this.removePlayer( window.mejs.players[p] );
			}
		}
	},

	/**
	 * Override the MediaElement method for removing a player.
	 *	MediaElement tries to pull the audio/video tag out of
	 *	its container and re-add it to the DOM.
	 */
	removePlayer: function(t) {
		var featureIndex, feature;

		if ( ! t.options ) {
			return;
		}

		// invoke features cleanup
		for ( featureIndex in t.options.features ) {
			feature = t.options.features[featureIndex];
			if ( t['clean' + feature] ) {
				try {
					t['clean' + feature](t);
				} catch (e) {}
			}
		}

		if ( ! t.isDynamic ) {
			t.$node.remove();
		}

		if ( 'native' !== t.media.pluginType ) {
			t.$media.remove();
		}

		delete window.mejs.players[t.id];

		t.container.remove();
		t.globalUnbind();
		delete t.node.player;
	},

	/**
	 * Allows any class that has set 'player' to a MediaElementPlayer
	 *  instance to remove the player when listening to events.
	 *
	 *  Examples: modal closes, shortcode properties are removed, etc.
	 */
	unsetPlayers : function() {
		if ( this.players && this.players.length ) {
			_.each( this.players, function (player) {
				player.pause();
				wp.media.mixin.removePlayer( player );
			} );
			this.players = [];
		}
	}
};

/**
 * Autowire "collection"-type shortcodes
 */
wp.media.playlist = new wp.media.collection({
	tag: 'playlist',
	editTitle : l10n.editPlaylistTitle,
	defaults : {
		id: wp.media.view.settings.post.id,
		style: 'light',
		tracklist: true,
		tracknumbers: true,
		images: true,
		artists: true,
		type: 'audio'
	}
});

/**
 * Shortcode modeling for audio
 *  `edit()` prepares the shortcode for the media modal
 *  `shortcode()` builds the new shortcode after update
 *
 * @namespace
 */
wp.media.audio = {
	coerce : wp.media.coerce,

	defaults : {
		id : wp.media.view.settings.post.id,
		src : '',
		loop : false,
		autoplay : false,
		preload : 'none',
		width : 400
	},

	edit : function( data ) {
		var frame, shortcode = wp.shortcode.next( 'audio', data ).shortcode;

		frame = wp.media({
			frame: 'audio',
			state: 'audio-details',
			metadata: _.defaults( shortcode.attrs.named, this.defaults )
		});

		return frame;
	},

	shortcode : function( model ) {
		var content;

		_.each( this.defaults, function( value, key ) {
			model[ key ] = this.coerce( model, key );

			if ( value === model[ key ] ) {
				delete model[ key ];
			}
		}, this );

		content = model.content;
		delete model.content;

		return new wp.shortcode({
			tag: 'audio',
			attrs: model,
			content: content
		});
	}
};

/**
 * Shortcode modeling for video
 *  `edit()` prepares the shortcode for the media modal
 *  `shortcode()` builds the new shortcode after update
 *
 * @namespace
 */
wp.media.video = {
	coerce : wp.media.coerce,

	defaults : {
		id : wp.media.view.settings.post.id,
		src : '',
		poster : '',
		loop : false,
		autoplay : false,
		preload : 'metadata',
		content : '',
		width : 640,
		height : 360
	},

	edit : function( data ) {
		var frame,
			shortcode = wp.shortcode.next( 'video', data ).shortcode,
			attrs;

		attrs = shortcode.attrs.named;
		attrs.content = shortcode.content;

		frame = wp.media({
			frame: 'video',
			state: 'video-details',
			metadata: _.defaults( attrs, this.defaults )
		});

		return frame;
	},

	shortcode : function( model ) {
		var content;

		_.each( this.defaults, function( value, key ) {
			model[ key ] = this.coerce( model, key );

			if ( value === model[ key ] ) {
				delete model[ key ];
			}
		}, this );

		content = model.content;
		delete model.content;

		return new wp.shortcode({
			tag: 'video',
			attrs: model,
			content: content
		});
	}
};

media.model.PostMedia = require( './models/post-media.js' );
media.controller.AudioDetails = require( './controllers/audio-details.js' );
media.controller.VideoDetails = require( './controllers/video-details.js' );
media.view.MediaFrame.MediaDetails = require( './views/frame/media-details.js' );
media.view.MediaFrame.AudioDetails = require( './views/frame/audio-details.js' );
media.view.MediaFrame.VideoDetails = require( './views/frame/video-details.js' );
media.view.MediaDetails = require( './views/media-details.js' );
media.view.AudioDetails = require( './views/audio-details.js' );
media.view.VideoDetails = require( './views/video-details.js' );

},{"./controllers/audio-details.js":2,"./controllers/video-details.js":3,"./models/post-media.js":4,"./views/audio-details.js":5,"./views/frame/audio-details.js":6,"./views/frame/media-details.js":7,"./views/frame/video-details.js":8,"./views/media-details.js":9,"./views/video-details.js":10}],2:[function(require,module,exports){
/**
 * wp.media.controller.AudioDetails
 *
 * The controller for the Audio Details state
 *
 * @class
 * @augments wp.media.controller.State
 * @augments Backbone.Model
 */
var State = wp.media.controller.State,
	l10n = wp.media.view.l10n,
	AudioDetails;

AudioDetails = State.extend({
	defaults: {
		id: 'audio-details',
		toolbar: 'audio-details',
		title: l10n.audioDetailsTitle,
		content: 'audio-details',
		menu: 'audio-details',
		router: false,
		priority: 60
	},

	initialize: function( options ) {
		this.media = options.media;
		State.prototype.initialize.apply( this, arguments );
	}
});

module.exports = AudioDetails;

},{}],3:[function(require,module,exports){
/**
 * wp.media.controller.VideoDetails
 *
 * The controller for the Video Details state
 *
 * @class
 * @augments wp.media.controller.State
 * @augments Backbone.Model
 */
var State = wp.media.controller.State,
	l10n = wp.media.view.l10n,
	VideoDetails;

VideoDetails = State.extend({
	defaults: {
		id: 'video-details',
		toolbar: 'video-details',
		title: l10n.videoDetailsTitle,
		content: 'video-details',
		menu: 'video-details',
		router: false,
		priority: 60
	},

	initialize: function( options ) {
		this.media = options.media;
		State.prototype.initialize.apply( this, arguments );
	}
});

module.exports = VideoDetails;

},{}],4:[function(require,module,exports){
/**
 * wp.media.model.PostMedia
 *
 * Shared model class for audio and video. Updates the model after
 *   "Add Audio|Video Source" and "Replace Audio|Video" states return
 *
 * @class
 * @augments Backbone.Model
 */
var PostMedia = Backbone.Model.extend({
	initialize: function() {
		this.attachment = false;
	},

	setSource: function( attachment ) {
		this.attachment = attachment;
		this.extension = attachment.get( 'filename' ).split('.').pop();

		if ( this.get( 'src' ) && this.extension === this.get( 'src' ).split('.').pop() ) {
			this.unset( 'src' );
		}

		if ( _.contains( wp.media.view.settings.embedExts, this.extension ) ) {
			this.set( this.extension, this.attachment.get( 'url' ) );
		} else {
			this.unset( this.extension );
		}
	},

	changeAttachment: function( attachment ) {
		this.setSource( attachment );

		this.unset( 'src' );
		_.each( _.without( wp.media.view.settings.embedExts, this.extension ), function( ext ) {
			this.unset( ext );
		}, this );
	}
});

module.exports = PostMedia;

},{}],5:[function(require,module,exports){
/**
 * wp.media.view.AudioDetails
 *
 * @class
 * @augments wp.media.view.MediaDetails
 * @augments wp.media.view.Settings.AttachmentDisplay
 * @augments wp.media.view.Settings
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var MediaDetails = wp.media.view.MediaDetails,
	AudioDetails;

AudioDetails = MediaDetails.extend({
	className: 'audio-details',
	template:  wp.template('audio-details'),

	setMedia: function() {
		var audio = this.$('.wp-audio-shortcode');

		if ( audio.find( 'source' ).length ) {
			if ( audio.is(':hidden') ) {
				audio.show();
			}
			this.media = MediaDetails.prepareSrc( audio.get(0) );
		} else {
			audio.hide();
			this.media = false;
		}

		return this;
	}
});

module.exports = AudioDetails;

},{}],6:[function(require,module,exports){
/**
 * wp.media.view.MediaFrame.AudioDetails
 *
 * @class
 * @augments wp.media.view.MediaFrame.MediaDetails
 * @augments wp.media.view.MediaFrame.Select
 * @augments wp.media.view.MediaFrame
 * @augments wp.media.view.Frame
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 * @mixes wp.media.controller.StateMachine
 */
var MediaDetails = wp.media.view.MediaFrame.MediaDetails,
	MediaLibrary = wp.media.controller.MediaLibrary,

	l10n = wp.media.view.l10n,
	AudioDetails;

AudioDetails = MediaDetails.extend({
	defaults: {
		id:      'audio',
		url:     '',
		menu:    'audio-details',
		content: 'audio-details',
		toolbar: 'audio-details',
		type:    'link',
		title:    l10n.audioDetailsTitle,
		priority: 120
	},

	initialize: function( options ) {
		options.DetailsView = wp.media.view.AudioDetails;
		options.cancelText = l10n.audioDetailsCancel;
		options.addText = l10n.audioAddSourceTitle;

		MediaDetails.prototype.initialize.call( this, options );
	},

	bindHandlers: function() {
		MediaDetails.prototype.bindHandlers.apply( this, arguments );

		this.on( 'toolbar:render:replace-audio', this.renderReplaceToolbar, this );
		this.on( 'toolbar:render:add-audio-source', this.renderAddSourceToolbar, this );
	},

	createStates: function() {
		this.states.add([
			new wp.media.controller.AudioDetails( {
				media: this.media
			} ),

			new MediaLibrary( {
				type: 'audio',
				id: 'replace-audio',
				title: l10n.audioReplaceTitle,
				toolbar: 'replace-audio',
				media: this.media,
				menu: 'audio-details'
			} ),

			new MediaLibrary( {
				type: 'audio',
				id: 'add-audio-source',
				title: l10n.audioAddSourceTitle,
				toolbar: 'add-audio-source',
				media: this.media,
				menu: false
			} )
		]);
	}
});

module.exports = AudioDetails;

},{}],7:[function(require,module,exports){
/**
 * wp.media.view.MediaFrame.MediaDetails
 *
 * @class
 * @augments wp.media.view.MediaFrame.Select
 * @augments wp.media.view.MediaFrame
 * @augments wp.media.view.Frame
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 * @mixes wp.media.controller.StateMachine
 */
var Select = wp.media.view.MediaFrame.Select,
	l10n = wp.media.view.l10n,
	MediaDetails;

MediaDetails = Select.extend({
	defaults: {
		id:      'media',
		url:     '',
		menu:    'media-details',
		content: 'media-details',
		toolbar: 'media-details',
		type:    'link',
		priority: 120
	},

	initialize: function( options ) {
		this.DetailsView = options.DetailsView;
		this.cancelText = options.cancelText;
		this.addText = options.addText;

		this.media = new wp.media.model.PostMedia( options.metadata );
		this.options.selection = new wp.media.model.Selection( this.media.attachment, { multiple: false } );
		Select.prototype.initialize.apply( this, arguments );
	},

	bindHandlers: function() {
		var menu = this.defaults.menu;

		Select.prototype.bindHandlers.apply( this, arguments );

		this.on( 'menu:create:' + menu, this.createMenu, this );
		this.on( 'content:render:' + menu, this.renderDetailsContent, this );
		this.on( 'menu:render:' + menu, this.renderMenu, this );
		this.on( 'toolbar:render:' + menu, this.renderDetailsToolbar, this );
	},

	renderDetailsContent: function() {
		var view = new this.DetailsView({
			controller: this,
			model: this.state().media,
			attachment: this.state().media.attachment
		}).render();

		this.content.set( view );
	},

	renderMenu: function( view ) {
		var lastState = this.lastState(),
			previous = lastState && lastState.id,
			frame = this;

		view.set({
			cancel: {
				text:     this.cancelText,
				priority: 20,
				click:    function() {
					if ( previous ) {
						frame.setState( previous );
					} else {
						frame.close();
					}
				}
			},
			separateCancel: new wp.media.View({
				className: 'separator',
				priority: 40
			})
		});

	},

	setPrimaryButton: function(text, handler) {
		this.toolbar.set( new wp.media.view.Toolbar({
			controller: this,
			items: {
				button: {
					style:    'primary',
					text:     text,
					priority: 80,
					click:    function() {
						var controller = this.controller;
						handler.call( this, controller, controller.state() );
						// Restore and reset the default state.
						controller.setState( controller.options.state );
						controller.reset();
					}
				}
			}
		}) );
	},

	renderDetailsToolbar: function() {
		this.setPrimaryButton( l10n.update, function( controller, state ) {
			controller.close();
			state.trigger( 'update', controller.media.toJSON() );
		} );
	},

	renderReplaceToolbar: function() {
		this.setPrimaryButton( l10n.replace, function( controller, state ) {
			var attachment = state.get( 'selection' ).single();
			controller.media.changeAttachment( attachment );
			state.trigger( 'replace', controller.media.toJSON() );
		} );
	},

	renderAddSourceToolbar: function() {
		this.setPrimaryButton( this.addText, function( controller, state ) {
			var attachment = state.get( 'selection' ).single();
			controller.media.setSource( attachment );
			state.trigger( 'add-source', controller.media.toJSON() );
		} );
	}
});

module.exports = MediaDetails;

},{}],8:[function(require,module,exports){
/**
 * wp.media.view.MediaFrame.VideoDetails
 *
 * @class
 * @augments wp.media.view.MediaFrame.MediaDetails
 * @augments wp.media.view.MediaFrame.Select
 * @augments wp.media.view.MediaFrame
 * @augments wp.media.view.Frame
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 * @mixes wp.media.controller.StateMachine
 */
var MediaDetails = wp.media.view.MediaFrame.MediaDetails,
	MediaLibrary = wp.media.controller.MediaLibrary,
	l10n = wp.media.view.l10n,
	VideoDetails;

VideoDetails = MediaDetails.extend({
	defaults: {
		id:      'video',
		url:     '',
		menu:    'video-details',
		content: 'video-details',
		toolbar: 'video-details',
		type:    'link',
		title:    l10n.videoDetailsTitle,
		priority: 120
	},

	initialize: function( options ) {
		options.DetailsView = wp.media.view.VideoDetails;
		options.cancelText = l10n.videoDetailsCancel;
		options.addText = l10n.videoAddSourceTitle;

		MediaDetails.prototype.initialize.call( this, options );
	},

	bindHandlers: function() {
		MediaDetails.prototype.bindHandlers.apply( this, arguments );

		this.on( 'toolbar:render:replace-video', this.renderReplaceToolbar, this );
		this.on( 'toolbar:render:add-video-source', this.renderAddSourceToolbar, this );
		this.on( 'toolbar:render:select-poster-image', this.renderSelectPosterImageToolbar, this );
		this.on( 'toolbar:render:add-track', this.renderAddTrackToolbar, this );
	},

	createStates: function() {
		this.states.add([
			new wp.media.controller.VideoDetails({
				media: this.media
			}),

			new MediaLibrary( {
				type: 'video',
				id: 'replace-video',
				title: l10n.videoReplaceTitle,
				toolbar: 'replace-video',
				media: this.media,
				menu: 'video-details'
			} ),

			new MediaLibrary( {
				type: 'video',
				id: 'add-video-source',
				title: l10n.videoAddSourceTitle,
				toolbar: 'add-video-source',
				media: this.media,
				menu: false
			} ),

			new MediaLibrary( {
				type: 'image',
				id: 'select-poster-image',
				title: l10n.videoSelectPosterImageTitle,
				toolbar: 'select-poster-image',
				media: this.media,
				menu: 'video-details'
			} ),

			new MediaLibrary( {
				type: 'text',
				id: 'add-track',
				title: l10n.videoAddTrackTitle,
				toolbar: 'add-track',
				media: this.media,
				menu: 'video-details'
			} )
		]);
	},

	renderSelectPosterImageToolbar: function() {
		this.setPrimaryButton( l10n.videoSelectPosterImageTitle, function( controller, state ) {
			var urls = [], attachment = state.get( 'selection' ).single();

			controller.media.set( 'poster', attachment.get( 'url' ) );
			state.trigger( 'set-poster-image', controller.media.toJSON() );

			_.each( wp.media.view.settings.embedExts, function (ext) {
				if ( controller.media.get( ext ) ) {
					urls.push( controller.media.get( ext ) );
				}
			} );

			wp.ajax.send( 'set-attachment-thumbnail', {
				data : {
					urls: urls,
					thumbnail_id: attachment.get( 'id' )
				}
			} );
		} );
	},

	renderAddTrackToolbar: function() {
		this.setPrimaryButton( l10n.videoAddTrackTitle, function( controller, state ) {
			var attachment = state.get( 'selection' ).single(),
				content = controller.media.get( 'content' );

			if ( -1 === content.indexOf( attachment.get( 'url' ) ) ) {
				content += [
					'<track srclang="en" label="English" kind="subtitles" src="',
					attachment.get( 'url' ),
					'" />'
				].join('');

				controller.media.set( 'content', content );
			}
			state.trigger( 'add-track', controller.media.toJSON() );
		} );
	}
});

module.exports = VideoDetails;

},{}],9:[function(require,module,exports){
/* global MediaElementPlayer */

/**
 * wp.media.view.MediaDetails
 *
 * @class
 * @augments wp.media.view.Settings.AttachmentDisplay
 * @augments wp.media.view.Settings
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var AttachmentDisplay = wp.media.view.Settings.AttachmentDisplay,
	$ = jQuery,
	MediaDetails;

MediaDetails = AttachmentDisplay.extend({
	initialize: function() {
		_.bindAll(this, 'success');
		this.players = [];
		this.listenTo( this.controller, 'close', wp.media.mixin.unsetPlayers );
		this.on( 'ready', this.setPlayer );
		this.on( 'media:setting:remove', wp.media.mixin.unsetPlayers, this );
		this.on( 'media:setting:remove', this.render );
		this.on( 'media:setting:remove', this.setPlayer );
		this.events = _.extend( this.events, {
			'click .remove-setting' : 'removeSetting',
			'change .content-track' : 'setTracks',
			'click .remove-track' : 'setTracks',
			'click .add-media-source' : 'addSource'
		} );

		AttachmentDisplay.prototype.initialize.apply( this, arguments );
	},

	prepare: function() {
		return _.defaults({
			model: this.model.toJSON()
		}, this.options );
	},

	/**
	 * Remove a setting's UI when the model unsets it
	 *
	 * @fires wp.media.view.MediaDetails#media:setting:remove
	 *
	 * @param {Event} e
	 */
	removeSetting : function(e) {
		var wrap = $( e.currentTarget ).parent(), setting;
		setting = wrap.find( 'input' ).data( 'setting' );

		if ( setting ) {
			this.model.unset( setting );
			this.trigger( 'media:setting:remove', this );
		}

		wrap.remove();
	},

	/**
	 *
	 * @fires wp.media.view.MediaDetails#media:setting:remove
	 */
	setTracks : function() {
		var tracks = '';

		_.each( this.$('.content-track'), function(track) {
			tracks += $( track ).val();
		} );

		this.model.set( 'content', tracks );
		this.trigger( 'media:setting:remove', this );
	},

	addSource : function( e ) {
		this.controller.lastMime = $( e.currentTarget ).data( 'mime' );
		this.controller.setState( 'add-' + this.controller.defaults.id + '-source' );
	},

	loadPlayer: function () {
		this.players.push( new MediaElementPlayer( this.media, this.settings ) );
		this.scriptXhr = false;
	},

	/**
	 * @global MediaElementPlayer
	 */
	setPlayer : function() {
		var baseSettings;

		if ( this.players.length || ! this.media || this.scriptXhr ) {
			return;
		}

		if ( this.model.get( 'src' ).indexOf( 'vimeo' ) > -1 && ! ( 'Froogaloop' in window ) ) {
			baseSettings = wp.media.mixin.mejsSettings;
			this.scriptXhr = $.getScript( baseSettings.pluginPath + 'froogaloop.min.js', _.bind( this.loadPlayer, this ) );
		} else {
			this.loadPlayer();
		}
	},

	/**
	 * @abstract
	 */
	setMedia : function() {
		return this;
	},

	success : function(mejs) {
		var autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;

		if ( 'flash' === mejs.pluginType && autoplay ) {
			mejs.addEventListener( 'canplay', function() {
				mejs.play();
			}, false );
		}

		this.mejs = mejs;
	},

	/**
	 * @returns {media.view.MediaDetails} Returns itself to allow chaining
	 */
	render: function() {
		AttachmentDisplay.prototype.render.apply( this, arguments );

		setTimeout( _.bind( function() {
			this.resetFocus();
		}, this ), 10 );

		this.settings = _.defaults( {
			success : this.success
		}, wp.media.mixin.mejsSettings );

		return this.setMedia();
	},

	resetFocus: function() {
		this.$( '.embed-media-settings' ).scrollTop( 0 );
	}
}, {
	instances : 0,
	/**
	 * When multiple players in the DOM contain the same src, things get weird.
	 *
	 * @param {HTMLElement} elem
	 * @returns {HTMLElement}
	 */
	prepareSrc : function( elem ) {
		var i = MediaDetails.instances++;
		_.each( $( elem ).find( 'source' ), function( source ) {
			source.src = [
				source.src,
				source.src.indexOf('?') > -1 ? '&' : '?',
				'_=',
				i
			].join('');
		} );

		return elem;
	}
});

module.exports = MediaDetails;

},{}],10:[function(require,module,exports){
/**
 * wp.media.view.VideoDetails
 *
 * @class
 * @augments wp.media.view.MediaDetails
 * @augments wp.media.view.Settings.AttachmentDisplay
 * @augments wp.media.view.Settings
 * @augments wp.media.View
 * @augments wp.Backbone.View
 * @augments Backbone.View
 */
var MediaDetails = wp.media.view.MediaDetails,
	VideoDetails;

VideoDetails = MediaDetails.extend({
	className: 'video-details',
	template:  wp.template('video-details'),

	setMedia: function() {
		var video = this.$('.wp-video-shortcode');

		if ( video.find( 'source' ).length ) {
			if ( video.is(':hidden') ) {
				video.show();
			}

			if ( ! video.hasClass( 'youtube-video' ) && ! video.hasClass( 'vimeo-video' ) ) {
				this.media = MediaDetails.prepareSrc( video.get(0) );
			} else {
				this.media = video.get(0);
			}
		} else {
			video.hide();
			this.media = false;
		}

		return this;
	}
});

module.exports = VideoDetails;

},{}]},{},[1]);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             /* global tinymce */

/*
 * The TinyMCE view API.
 *
 * Note: this API is "experimental" meaning that it will probably change
 * in the next few releases based on feedback from 3.9.0.
 * If you decide to use it, please follow the development closely.
 *
 * Diagram
 *
 * |- registered view constructor (type)
 * |  |- view instance (unique text)
 * |  |  |- editor 1
 * |  |  |  |- view node
 * |  |  |  |- view node
 * |  |  |  |- ...
 * |  |  |- editor 2
 * |  |  |  |- ...
 * |  |- view instance
 * |  |  |- ...
 * |- registered view
 * |  |- ...
 */
( function( window, wp, shortcode, $ ) {
	'use strict';

	var views = {},
		instances = {};

	wp.mce = wp.mce || {};

	/**
	 * wp.mce.views
	 *
	 * A set of utilities that simplifies adding custom UI within a TinyMCE editor.
	 * At its core, it serves as a series of converters, transforming text to a
	 * custom UI, and back again.
	 */
	wp.mce.views = {

		/**
		 * Registers a new view type.
		 *
		 * @param {String} type   The view type.
		 * @param {Object} extend An object to extend wp.mce.View.prototype with.
		 */
		register: function( type, extend ) {
			views[ type ] = wp.mce.View.extend( _.extend( extend, { type: type } ) );
		},

		/**
		 * Unregisters a view type.
		 *
		 * @param {String} type The view type.
		 */
		unregister: function( type ) {
			delete views[ type ];
		},

		/**
		 * Returns the settings of a view type.
		 *
		 * @param {String} type The view type.
		 *
		 * @return {Function} The view constructor.
		 */
		get: function( type ) {
			return views[ type ];
		},

		/**
		 * Unbinds all view nodes.
		 * Runs before removing all view nodes from the DOM.
		 */
		unbind: function() {
			_.each( instances, function( instance ) {
				instance.unbind();
			} );
		},

		/**
		 * Scans a given string for each view's pattern,
		 * replacing any matches with markers,
		 * and creates a new instance for every match.
		 *
		 * @param {String} content The string to scan.
		 *
		 * @return {String} The string with markers.
		 */
		setMarkers: function( content ) {
			var pieces = [ { content: content } ],
				self = this,
				instance, current;

			_.each( views, function( view, type ) {
				current = pieces.slice();
				pieces  = [];

				_.each( current, function( piece ) {
					var remaining = piece.content,
						result, text;

					// Ignore processed pieces, but retain their location.
					if ( piece.processed ) {
						pieces.push( piece );
						return;
					}

					// Iterate through the string progressively matching views
					// and slicing the string as we go.
					while ( remaining && ( result = view.prototype.match( remaining ) ) ) {
						// Any text before the match becomes an unprocessed piece.
						if ( result.index ) {
							pieces.push( { content: remaining.substring( 0, result.index ) } );
						}

						instance = self.createInstance( type, result.content, result.options );
						text = instance.loader ? '.' : instance.text;

						// Add the processed piece for the match.
						pieces.push( {
							content: '<p data-wpview-marker="' + instance.encodedText + '">' + text + '</p>',
							processed: true
						} );

						// Update the remaining content.
						remaining = remaining.slice( result.index + result.content.length );
					}

					// There are no additional matches.
					// If any content remains, add it as an unprocessed piece.
					if ( remaining ) {
						pieces.push( { content: remaining } );
					}
				} );
			} );

			content = _.pluck( pieces, 'content' ).join( '' );
			return content.replace( /<p>\s*<p data-wpview-marker=/g, '<p data-wpview-marker=' ).replace( /<\/p>\s*<\/p>/g, '</p>' );
		},

		/**
		 * Create a view instance.
		 *
		 * @param {String}  type    The view type.
		 * @param {String}  text    The textual representation of the view.
		 * @param {Object}  options Options.
		 * @param {Boolean} force   Recreate the instance. Optional.
		 *
		 * @return {wp.mce.View} The view instance.
		 */
		createInstance: function( type, text, options, force ) {
			var View = this.get( type ),
				encodedText,
				instance;

			text = tinymce.DOM.decode( text );

			if ( ! force ) {
				instance = this.getInstance( text );

				if ( instance ) {
					return instance;
				}
			}

			encodedText = encodeURIComponent( text );

			options = _.extend( options || {}, {
				text: text,
				encodedText: encodedText
			} );

			return instances[ encodedText ] = new View( options );
		},

		/**
		 * Get a view instance.
		 *
		 * @param {(String|HTMLElement)} object The textual representation of the view or the view node.
		 *
		 * @return {wp.mce.View} The view instance or undefined.
		 */
		getInstance: function( object ) {
			if ( typeof object === 'string' ) {
				return instances[ encodeURIComponent( object ) ];
			}

			return instances[ $( object ).attr( 'data-wpview-text' ) ];
		},

		/**
		 * Given a view node, get the view's text.
		 *
		 * @param {HTMLElement} node The view node.
		 *
		 * @return {String} The textual representation of the view.
		 */
		getText: function( node ) {
			return decodeURIComponent( $( node ).attr( 'data-wpview-text' ) || '' );
		},

		/**
		 * Renders all view nodes that are not yet rendered.
		 *
		 * @param {Boolean} force Rerender all view nodes.
		 */
		render: function( force ) {
			_.each( instances, function( instance ) {
				instance.render( force );
			} );
		},

		/**
		 * Update the text of a given view node.
		 *
		 * @param {String}         text   The new text.
		 * @param {tinymce.Editor} editor The TinyMCE editor instance the view node is in.
		 * @param {HTMLElement}    node   The view node to update.
		 * @param {Boolean}        force  Recreate the instance. Optional.
		 */
		update: function( text, editor, node, force ) {
			var instance = this.getInstance( node );

			if ( instance ) {
				instance.update( text, editor, node, force );
			}
		},

		/**
		 * Renders any editing interface based on the view type.
		 *
		 * @param {tinymce.Editor} editor The TinyMCE editor instance the view node is in.
		 * @param {HTMLElement}    node   The view node to edit.
		 */
		edit: function( editor, node ) {
			var instance = this.getInstance( node );

			if ( instance && instance.edit ) {
				instance.edit( instance.text, function( text, force ) {
					instance.update( text, editor, node, force );
				} );
			}
		},

		/**
		 * Remove a given view node from the DOM.
		 *
		 * @param {tinymce.Editor} editor The TinyMCE editor instance the view node is in.
		 * @param {HTMLElement}    node   The view node to remove.
		 */
		remove: function( editor, node ) {
			var instance = this.getInstance( node );

			if ( instance ) {
				instance.remove( editor, node );
			}
		}
	};

	/**
	 * A Backbone-like View constructor intended for use when rendering a TinyMCE View.
	 * The main difference is that the TinyMCE View is not tied to a particular DOM node.
	 *
	 * @param {Object} options Options.
	 */
	wp.mce.View = function( options ) {
		_.extend( this, options );
		this.initialize();
	};

	wp.mce.View.extend = Backbone.View.extend;

	_.extend( wp.mce.View.prototype, {

		/**
		 * The content.
		 *
		 * @type {*}
		 */
		content: null,

		/**
		 * Whether or not to display a loader.
		 *
		 * @type {Boolean}
		 */
		loader: true,

		/**
		 * Runs after the view instance is created.
		 */
		initialize: function() {},

		/**
		 * Retuns the content to render in the view node.
		 *
		 * @return {*}
		 */
		getContent: function() {
			return this.content;
		},

		/**
		 * Renders all view nodes tied to this view instance that are not yet rendered.
		 *
		 * @param {String}  content The content to render. Optional.
		 * @param {Boolean} force   Rerender all view nodes tied to this view instance. Optional.
		 */
		render: function( content, force ) {
			if ( content != null ) {
				this.content = content;
			}

			content = this.getContent();

			// If there's nothing to render an no loader needs to be shown, stop.
			if ( ! this.loader && ! content ) {
				return;
			}

			// We're about to rerender all views of this instance, so unbind rendered views.
			force && this.unbind();

			// Replace any left over markers.
			this.replaceMarkers();

			if ( content ) {
				this.setContent( content, function( editor, node, contentNode ) {
					$( node ).data( 'rendered', true );
					this.bindNode.call( this, editor, node, contentNode );
				}, force ? null : false );
			} else {
				this.setLoader();
			}
		},

		/**
		 * Binds a given node after its content is added to the DOM.
		 */
		bindNode: function() {},

		/**
		 * Unbinds a given node before its content is removed from the DOM.
		 */
		unbindNode: function() {},

		/**
		 * Unbinds all view nodes tied to this view instance.
		 * Runs before their content is removed from the DOM.
		 */
		unbind: function() {
			this.getNodes( function( editor, node, contentNode ) {
				this.unbindNode.call( this, editor, node, contentNode );
				$( node ).trigger( 'wp-mce-view-unbind' );
			}, true );
		},

		/**
		 * Gets all the TinyMCE editor instances that support views.
		 *
		 * @param {Function} callback A callback.
		 */
		getEditors: function( callback ) {
			_.each( tinymce.editors, function( editor ) {
				if ( editor.plugins.wpview ) {
					callback.call( this, editor );
				}
			}, this );
		},

		/**
		 * Gets all view nodes tied to this view instance.
		 *
		 * @param {Function} callback A callback.
		 * @param {Boolean}  rendered Get (un)rendered view nodes. Optional.
		 */
		getNodes: function( callback, rendered ) {
			this.getEditors( function( editor ) {
				var self = this;

				$( editor.getBody() )
					.find( '[data-wpview-text="' + self.encodedText + '"]' )
					.filter( function() {
						var data;

						if ( rendered == null ) {
							return true;
						}

						data = $( this ).data( 'rendered' ) === true;

						return rendered ? data : ! data;
					} )
					.each( function() {
						callback.call( self, editor, this, $( this ).find( '.wpview-content' ).get( 0 ) );
					} );
			} );
		},

		/**
		 * Gets all marker nodes tied to this view instance.
		 *
		 * @param {Function} callback A callback.
		 */
		getMarkers: function( callback ) {
			this.getEditors( function( editor ) {
				var self = this;

				$( editor.getBody() )
					.find( '[data-wpview-marker="' + this.encodedText + '"]' )
					.each( function() {
						callback.call( self, editor, this );
					} );
			} );
		},

		/**
		 * Replaces all marker nodes tied to this view instance.
		 */
		replaceMarkers: function() {
			this.getMarkers( function( editor, node ) {
				var selected = node === editor.selection.getNode(),
					$viewNode;

				if ( ! this.loader && $( node ).text() !== this.text ) {
					editor.dom.setAttrib( node, 'data-wpview-marker', null );
					return;
				}

				$viewNode = editor.$(
					'<div class="wpview-wrap" data-wpview-text="' + this.encodedText + '" data-wpview-type="' + this.type + '">' +
						'<p class="wpview-selection-before">\u00a0</p>' +
						'<div class="wpview-body" contenteditable="false">' +
							'<div class="w