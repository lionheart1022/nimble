er-color: $successBorder;
    background-color: lighten($successBackground, 7.5%);
  }
}



// Button Icons
// =============================================================================

.woocommerce,
.woocommerce-page {
  .button {
    &.product_type_simple:before,
    &.product_type_variable:before,
    &.single_add_to_cart_button:before {
      @include font-awesome();
    }

    &.product_type_simple:before,
    &.single_add_to_cart_button:before {
      content: "\f07a\0020";
    }

    &.product_type_variable:before {
      content: "\f14a\0020";
    }
  }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   // =============================================================================
// SCSS/SITE/STACKS/ICON/_SHORTCODES.SCSS
// -----------------------------------------------------------------------------
// Styles for shortcodes.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Dropcap
//   02. Horizontal Rule
//   03. Gap
//   04. Clear
//   05. Highlight
//   06. Quotes
//       a. Blockquote
//       b. Pullquote
//       c. Cite
//   07. Alert
//   08. Maps
//   09. Skill Bar
//   10. Code
//   11. Buttons
//       a. Individual Buttons
//       b. Button Group
//   12. Icons
//   13. Block Grid
//       a. Grid
//       b. Grid Item
//   14. Images
//   15. Icon list
//       a. List
//       b. List Item
//   16. Popovers and Tooltips
//   17. Text Columns
//   18. Video
//   19. Accordion
//       a. Accordion
//       b. Accordion Item
//   20. Tabbed Content
//       a. Tab Nav
//       b. Tab Nav Item
//       c. Tabs
//       d. Tab
//   21. Responsive Visibility
//   22. Content Columns
//   23. Responsive Slider
//       a. Slider
//       b. Slide
//   24. Protected Content
//   25. Recent Posts
//   26. Audio
//   27. Responsive Pricing Table
//   28. Callout
//   29. Promo
//   30. Post Author
//   31. Prompt
//   32. Content Band
//   33. Entry Share
//   34. Table of Contents
//       a. Container
//       b. Item
//   35. Custom Headline
//   36. Feature Headline
//   37. Search
//   38. Counter
//   39. Shortcode Container
// =============================================================================

// Dropcaps
// =============================================================================

.x-dropcap {
  float: left;
  display: block;
  margin: 0.2em 0.215em 0 0;
  padding: 0.105em 0.2em 0.11em;
  font-size: 3.2em;
  font-weight: bold;
  line-height: 1;
  color: $white;
  background-color: $accentColor;
}



// Horizontal Rules
// =============================================================================

.x-hr {  }



// Gaps
// =============================================================================

.x-gap {  }



// Clear
// =============================================================================

.x-clear {  }



// Highlight
// =============================================================================

.x-highlight {
  padding: 0.188em 0.375em;
  color: $white;
  background-color: $linkColor;

  &.dark {
    color: $white;
    background-color: darken($gray, 5%);
  }
}



// Quotes
// =============================================================================

//
// 1. Blockquote.
// 2. Pullquote.
// 3. Cite.
//

.x-blockquote { // 1
  &.right-text {
    text-align: right;
  }

  &.center-text {
    text-align: center;
  }
}

.x-pullquote { // 2
  width: 40%;
  margin: 0.45em 1.1em 0.55em 0;
  font-size: 1.313em;

  &.right {
    margin-right: 0;
    margin-left: 1.1em;
  }

  &.left,
  &.right {
    @include break(middle-bear) {
      float: none;
      width: 100%;
      margin: $baseMargin 0;
    }
  }
}

.x-cite { // 3
  display: block;
  margin-top: 0.75em;
  font-size: 0.625em;
  font-weight: 400;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: darken($shortcodeBorderColor, 15%);

  &:before {
    content: "\2013\00A0";
  }
}



// Alert
// =============================================================================

//
// Close.
//

.close {
  float: right;
  @include font-size(1.8);
  font-weight: bold;
  line-height: $baseLineHeight;
  color: $black;
  @include opacity(0.4);
  @include transition(#{opacity 0.3s ease});
  
  &:hover {
    color: $black;
    text-decoration: none;
    cursor: pointer;
    @include opacity(0.6);
  }
}

button.close {
  padding: 0;
  cursor: pointer;
  background-color: transparent;
  border: 0;
  -webkit-appearance: none;
}


//
// Close alert.
//

.x-alert,
.wpcf7-response-output,
.buddypress #message,
.bbp-template-notice {
  margin-bottom: $baseMargin;
  border: 1px solid $warningBorder;
  padding: $alertPadding;
  @include font-size(1.4);
  line-height: $alertLineHeight;
  color: $warningText;
  background-color: $warningBackground;

  .h-alert {
    margin: $alertHeadingMargin;
    @include font-size(1.8);
    letter-spacing: -1px;
    line-height: 1.3;
    text-transform: none;
    color: inherit;
  }

  .close {
    position: relative;
    top: -10px;
    right: -26px;
    line-height: 1;
    color: $warningText;
  }
}

.x-alert-muted {
  background-color: $grayLighter;
  border-color:     darken($grayLighter, 12%);
  color:            $grayLight;

  .close {
    color: $grayLight;
  }
}

.x-alert-info,
.buddypress #message.info,
.bbp-template-notice.info {
  background-color: $infoBackground;
  border-color:     $infoBorder;
  color:            $infoText;

  .close {
    color: $infoText;
  }
}

.x-alert-success,
.wpcf7-mail-sent-ok,
.buddypress #message.updated,
.bbp-template-notice.updated {
  background-color: $successBackground;
  border-color:     $successBorder;
  color:            $successText;

  .close {
    color: $successText;
  }
}

.x-alert-danger,
.buddypress #message.error,
.bbp-template-notice.error {
  background-color: $errorBackground;
  border-color:     $errorBorder;
  color:            $errorText;

  .close {
    color: $errorText;
  }
}

.x-alert-block {
  padding: $alertBlockPadding;
}

.x-alert-block > p,
.x-alert-block > ul {
  margin-bottom: 0;
}

.x-alert-block p + p {
  margin-top: 6px;
}

.wpcf7-response-output {
  border-width: 1px !important;
  margin: $baseMargin 0 0 !important;
  padding: $alertBlockPadding !important;
}



// Maps
// =============================================================================

.x-map {  }



// Skill Bar
// =============================================================================

.h-skill-bar {
  margin-top: 0;
  @include font-size(1.1);
  font-weight: 700;
  letter-spacing: 1px;
  line-height: 1;
  text-transform: uppercase;
}

.x-skill-bar {
  height: 25px;
  margin-bottom: $baseMargin;
  background-color: $baseBorderSolid;
  background-color: $baseBorderRgba;
}

.x-skill-bar .bar {
  overflow: hidden;
  position: relative;
  width: 0%;
  height: 100%;
  float: left;
  background-color: $accentColor;
}

.x-skill-bar .bar .percent {
  position: absolute;
  right: 4px;
  bottom: 4px;
  height: 17px;
  padding: 0 5px;
  font-size: 11px;
  line-height: 17px;
  color: $baseModBackground;
  background-color: $headingsColor;
  background-color: rgba(0, 0, 0, 0.25);
}



// Code
// =============================================================================

.x-code {  }



// Buttons
// =============================================================================

.x-btn {  }



// Icons
// =============================================================================

.x-icon {  }



// Block Grid
// =============================================================================

.x-block-grid {  }



// Images
// =============================================================================

.x-img {
  margin-bottom: $baseMargin;

  &.left {
    margin: 0.35em $baseMargin 0.35em 0;
  }

  &.right {
    margin: 0.35em 0 0.35em $baseMargin;
  }
}


//
// Link hover styles.
//

a.x-img {
  display: block;

  > img {
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
}

a.x-img:not(.x-img-thumbnail) {
  @include translate3d(0, 0, 0);
  @include transition(#{opacity 0.3s ease});

  &:hover {
    opacity: 0.75;
  }
}


//
// Rounded.
//

.x-img-rounded {
  &,
  img {
    border-radius: 6px;
  }
}


//
// Circle.
//

.x-img-circle {
  &,
  img {
    border-radius: 100em;
  }
}


//
// Thumbnail.
//

.x-img-thumbnail {
  @include img_thumbnail();
}

a.x-img-thumbnail:hover {
  border-color: $accentColor;
}



// Icon List
// =============================================================================

.x-ul-icons {
  list-style: none;
  text-indent: -0.85em;

  li {
    [class^="x-icon-"],
    [class*=" x-icon-"] {
      width: 0.85em;
    }
  }
}



// Popovers and Tooltips
// =============================================================================

.x-extra {  }



// Text Columns
// =============================================================================

.x-columnize {
  @include content-columns( 2, 3em, 1px solid $baseBorderSolid );
  @include content-columns( 2, 3em, 1px solid $baseBorderRgba );
  margin: 0 0 $baseMargin;

  p:last-child,
  ul:last-child,
  ol:last-child {
    margin-bottom: 0;
  }

  @include break(baby-bear) { @include content-columns(1, 0, 0); }
}



// Video
// =============================================================================

.x-video {  }



// Accordion
// =============================================================================

//
// Parent container.
//

.x-accordion {
  margin-bottom: 1.375em;
}


//
// Group == beading + body.
//

.x-accordion-group {
  margin: 4px 0;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  @include transition(#{border-color 0.3s ease});
}

.x-accordion-heading {
  border-bottom: 0;

  .x-accordion-toggle {
    display: block;
    padding: 10px 15px;
    font-size: 128.6%;
    color: $headingsColor;
    background-color: darken($baseModBackground, 1.5%);

    &.collapsed {
      background-color: $baseModBackground;

      &:before {
        color: darken($baseModBackground, 15%);
        @include rotate(0);
      }
    }

    &:hover {
      background-color: darken($baseModBackground, 1.5%);

      &:before {
        color: darken($baseModBackground, 15%);
      }
    }

    &:before {
      content: "\f067";
      position: relative;
      display: inline-block;
      bottom: 0.1em;
      margin-right: 10px;
      font-size: 74%;
      color: darken($baseModBackground, 15%);
      @include rotate(45deg);
      @include transition(#{all 0.3s ease});
      @include font-awesome();
    }
  }
}


//
// General toggle styles.
//

.x-accordion-toggle {
  cursor: pointer;
}


//
// Accordion inner.
// Needs the styles because you can't animate properly with any stiles on the
// element.
//

.x-accordion-inner {
  padding: 15px;
  border-top: 1px solid $baseBorderSolid;
  border-top: 1px solid $baseBorderRgba;

  p:last-child,
  ul:last-child,
  ol:last-child {
    margin-bottom: 0;
  }
}



// Tabbed Content
// =============================================================================

.x-nav-tabs {  }

.x-nav-tabs-item {  }

.x-tab-content {  }

.x-nav-pane {  }



// Responsive Visibility
// =============================================================================

.x-visibility {  }



// Content Columns
// =============================================================================

@include columns();



// Responsive Slider
// =============================================================================

.x-flexslider-shortcode-container {  }



// Protected Content
// =============================================================================

.x-protect {
  padding: 7% 18%;
  text-align: center;
  background-color: darken($baseModBackground, 4%);

  label {
    margin: 0.75em 0 0;
    @include font-size(1.6);
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    max-width: 380px;
    text-align: center;
  }
}

.h-protect {
  @include font-size(2.4);
  line-height: 1.2;
  color: $headingsColor;
}

.x-btn-protect {
  margin-top: 0.75em;
}



// Recent Posts
// =============================================================================

.x-recent-posts {
  margin: 0 0 $baseMargin;

  + .x-recent-posts {
    margin-top: 4%;
  }

  a {
    overflow: hidden;
    float: left;
    display: block;
    margin: 0 4% 0 0;
    border: 1px solid $baseBorderSolid;
    border: 1px solid $baseBorderRgba;
    padding: 6px;
    color: #999;
    background-color: $baseModBackground;
    @include translate3d(0, 0, 0);

    &:last-child {
      margin-right: 0;
    }

    &:hover {
      color: #999;

      img {
        @include opacity(0);
      }

      .x-recent-posts-img {
        background-color: rgba(0, 0, 0, 0.5);
      }
    }

    &.no-image {
      .hentry {
        padding: 4px;
      }

      .x-recent-posts-content {
        border: 0;
      }

      &:hover {
        .h-recent-posts {
          @include opacity(0.75);
        }
      }
    }

    &.x-recent-post1 {
      width: 100%;
    }

    &.x-recent-post2 {
      width: 48%;
      @include break(middle-bear) {
        width: 100%;
        float: none;
        margin-right: 0;
        margin-bottom: 4%;

        &:last-child {
          margin-bottom: 0;
        }
      }
    }

    &.x-recent-post3 {
      width: 30.6666%;
      @include break(middle-bear) {
        width: 100%;
        float: none;
        margin-right: 0;
        margin-bottom: 4%;

        &:last-child {
          margin-bottom: 0;
        }
      }
    }

    &.x-recent-post4 {
      width: 22%;
      @include break(cubs) {
        width: 48%;

        &:first-child {
          margin-bottom: 4%;
        }

        &:nth-child(2n) {
          margin-right: 0;
          margin-bottom: 4%;
        }

        &:nth-child(3),
        &:nth-child(4) {
          margin-bottom: 0;
        }
      }
      @include break(middle-bear) {
        width: 100%;
        float: none;
        margin-right: 0;
        margin-bottom: 4%;

        &:nth-child(3) {
          margin-bottom: 4%;
        }

        &:last-child {
          margin-bottom: 0;
        }
      }
    }
  }

  article.hentry {
    &,
    & > .entry-wrap,
    &:first-child > .entry-wrap,
    &:last-child > .entry-wrap {
      margin: 0;
      border: 0;
      padding: 0;
    }
  }

  img {
    position: relative;
    min-width: 100%;
    z-index: 1;
    @include transition(#{opacity 0.6s ease});
  }

  .x-recent-posts-img {
    overflow: hidden;
    position: relative;
    padding-bottom: 55.8823529%;
    background-color: #999;
    background-color: rgba(0, 0, 0, 0.25);
    @include transition(#{background-color 0.3s ease});

    &:before {
      display: block;
      position: absolute;
      margin: -30px 0 0 -30px;
      top: 50%;
      left: 50%;
      width: 60px;
      height: 60px;
      @include font-size(3.2);
      line-height: 59px;
      text-align: center;
      vertical-align: middle;
      color: $white;
      background-color: rgba(0, 0, 0, 0.35);
      border-radius: 100em;
      z-index: 0;
      @include font-awesome();
    }
  }

  .has-post-thumbnail .x-recent-posts-img {
    padding: 0;
  }

  .format-standard .x-recent-posts-img:before { content: "\f0f6"; }
  .format-video .x-recent-posts-img:before    { content: "\f008"; }
  .format-audio .x-recent-posts-img:before    { content: "\f001"; }
  .format-image .x-recent-posts-img:before    { content: "\f083"; }
  .format-gallery .x-recent-posts-img:before  { content: "\f03e"; }
  .format-link .x-recent-posts-img:before     { content: "\f0c1"; }
  .format-quote .x-recent-posts-img:before    { content: "\f10d"; }
  .x-portfolio .x-recent-posts-img:before     {
    content: "\f067";
    line-height: 62px;
  }

  .x-recent-posts-content {
    border-top: 0;
    border: 1px solid $baseBorderSolid;
    border: 1px solid $baseBorderRgba;
    padding: 0.65em 0.75em 0.775em;
  }

  .h-recent-posts,
  .x-recent-posts-date {
    display: block;
    line-height: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .h-recent-posts {
    margin: 0 0 2px;
    padding-bottom: 4px;
    @include font-size(1.7);
    @include transition(#{opacity 0.3s ease});
  }

  .x-recent-posts-date {
    margin: 0;
    @include font-size(1.0);
    text-transform: uppercase;
  }

  &.vertical {
    a {
      float: none;

      &.x-recent-post1,
      &.x-recent-post2,
      &.x-recent-post3,
      &.x-recent-post4 {
        width: 100%;
        margin-bottom: 4%;

        &:last-child {
          margin-bottom: 0;
        }
      }
    }
  }
}

.js {
  .x-recent-posts {
    &[data-fade="true"] {
      a {
        opacity: 0;
      }
    }
  }
}



// Audio
// =============================================================================

.x-audio {  }



// Responsive Pricing Table
// =============================================================================

.x-pricing-table {
  width: 100%;
  margin: $baseMargin 0;

  &.one-column .x-pricing-column    { width: 100%;      }
  &.two-columns .x-pricing-column   { width: 50%;       }
  &.three-columns .x-pricing-column { width: 33.33333%; }
  &.four-columns .x-pricing-column  { width: 25%;       }
  &.five-columns .x-pricing-column  { width: 20%;       }

  @include break(cubs) {
    &[class*="-column"] .x-pricing-column {
      width: 50%;
    }

    &.three-columns .x-pricing-column:last-child,
    &.five-columns .x-pricing-column:last-child {
      width: 99.9%;
    }
  }

  @include break(middle-bear) {
    &[class*="-column"] .x-pricing-column {
      width: 100%;
    }
  }
}

.x-pricing-column {
  float: left;
  margin: 0 0 -1px -1px;
  text-align: center;
  background-color: darken($baseModBackground, 1%);
  @include break(middle-bear) {
    float: none;
    margin-left: 0;
  }

  h2 {
    border: 1px solid $shortcodeBorderColor;
    padding: 20px 20px 25px;
    letter-spacing: 0;
    line-height: 1.1;
    @include font-size(3.2);

    span {
      display: block;
      margin-top: 5px;
      @include font-size(1.3);
      letter-spacing: 2px;
      text-transform: uppercase;
      color: $black;
      @include opacity(0.5);
    }
  }

  &.featured {
    position: relative;
    margin-top: -20px;
    background-color: $baseModBackground;
    @include break(middle-bear) {
      margin-top: 0;
    }

    h2 {
      border: 0;
      padding-bottom: 28px;
      color: $white;
      background-color: $accentColor;
    }

    .x-pricing-column-info {
      padding-bottom: 50px;

      ul {
        margin-bottom: 40px;
      }
    }
  }
}

.x-pricing-column-info {
  border: 1px solid $shortcodeBorderColor;
  border-top: 0;
  padding: 10px 0 32px;

  .x-price {
    margin: 0;
    @include font-size(5.4);
    letter-spacing: -3px;
  }

  span {
    display: block;
    margin-top: -1px;
    @include font-size(1.2);
    letter-spacing: 2px;
    text-transform: uppercase;
    color: lighten($textColor, 25%);
  }

  p {
    margin: 0;
  }

  ul {
    margin: 15px 0 25px;

    > li {
      padding: 10px 40px 11px;
      @include font-size(1.4);
      line-height: 1.4;
      text-transform: uppercase;

      [class*="x-icon"] {
        margin-left: 0.85em;
      }

      &:first-child {
        border-top: 1px solid $shortcodeBorderColor;
      }

      &:last-child {
        border-bottom: 1px solid $shortcodeBorderColor;
      }
    }
  }

  .x-btn {
    margin-left: 20px;
    margin-right: 20px;
  }
}



// Callout
// =============================================================================

.x-callout {
  position: relative;
  margin: ($baseMargin * 2) 0;
  padding: 2.35em;
  @include font-size(2.1);
  background-color: $baseBorderSolid;
  background-color: $baseBorderRgba;
  @include break(cubs) {
    @include font-size(1.8);
  }
  @include break(baby-bear) {
    @include font-size(1.6);
  }

  &:before {
    content: "";
    display: block;
    position: absolute;
    top: 15px;
    left: 15px;
    right: 15px;
    bottom: 15px;
    background-color: $baseModBackground;
    z-index: 0;
  }

  .x-btn {
    font-size: 1em;
  }
}

.h-callout {
  position: relative;
  margin-top: 0;
  margin-bottom: 0.365em;
  font-size: 1.85em;
  line-height: 1.1;
}

.p-callout {
  position: relative;
  line-height: 1.4;
}



// Promo
// =============================================================================

.x-promo {
  overflow: hidden;
  margin-bottom: $baseMargin;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  background-color: $baseModBackground;

  img {
    min-width: 100%;
  }
}

.x-promo-content {
  border-top: 1px solid $baseBorderSolid;
  border-top: 1px solid $baseBorderRgba;
  padding: 1.5em;

  img {
    min-width: none;
  }
}



// Post Author
// =============================================================================

.x-author-box {
  margin: 0 0 $baseMargin;

  .h-about-the-author {
    margin: 0 0 0.925em;
    border-bottom: 1px solid $baseBorderSolid;
    border-bottom: 1px solid $baseBorderRgba;
    padding-bottom: 0.45em;
    @include font-size(1.2);
    letter-spacing: 1px;
    text-transform: uppercase;
    color: lighten($textColor, 35%);
  }

  .avatar {
    float: left;
    width: 90px;
    @include break(baby-bear) {
      display: none;
    }
  }

  .x-author-info {
    margin-left: 110px;
    @include break(baby-bear) {
      margin-left: 0;
    }
  }

  .h-author {
    margin-bottom: 0.35em;
    line-height: 1;
  }

  .x-author-social {
    display: inline-block;
    margin-right: 1em;
    @include font-size(1.3);
    white-space: nowrap;

    [class*="x-social"] {
      position: relative;
      top: 2px;
    }
  }

  .p-author {
    margin-top: 0.5em;
  }
}



// Prompt
// =============================================================================

.x-prompt {
  margin: 0 0 $baseMargin;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  padding: 1.75em;
  background-color: $baseModBackground;

  &.message-left {
    .x-prompt-section.x-prompt-section-message {
      padding-right: 2.25em;
      text-align: left;
    }
  }

  &.message-right {
    .x-prompt-section.x-prompt-section-message {
      padding-left: 2.25em;
      text-align: right;
    }
  }
}

.x-prompt-section {
  position: relative;
  display: table-cell;
  vertical-align: middle;

  p:last-child {
    margin-bottom: 0;
  }

  &.x-prompt-section-message {
    width: 46%;
    @include font-size(1.6);
    line-height: 1.4;
  }

  &.x-prompt-section-button {
    width: 30%;
  }
}

.h-prompt {
  margin-top: 0;
  @include font-size(2.8);
  line-height: 1.1;
}

@include break(middle-bear) {
  .x-prompt {
    display: block;

    &.message-left {
      .x-prompt-section.x-prompt-section-message {
        padding: 0 0 1.25em 0;
      }
    }

    &.message-right {
      .x-prompt-section.x-prompt-section-message {
        padding: 1.25em 0 0 0;
        text-align: left;
      }
    }
  }

  .x-prompt-section {
    display: block;

    &.x-prompt-section-message,
    &.x-prompt-section-button {
      width: 100%;
    }
  }
}



// Content Band
// =============================================================================

@include content_band();



// Entry Share
// =============================================================================

.x-entry-share {
  margin: 0 0 $baseMargin;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  border-left: 0;
  border-right: 0;
  padding: 10px 0;
  @include font-size(1.2);
  line-height: 1;
  text-align: center;

  p {
    margin: 8px 0 10px;
    font-weight: 400;
    text-transform: uppercase;
  }

  .x-share {
    display: inline-block;
    margin: 0 0.05em;
    width: 45px;
    height: 45px;
    @include font-size(2.4);
    line-height: 45px;

    &:hover {
      color: #fff;
      background-color: $linkColor;
    }
  }
}



// Table of Contents
// =============================================================================

//
// Container.
//

.x-toc {
  width: 210px;
  margin: 0.55em 0;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  padding: 15px;

  &.left  { margin-right: 1.75em; }
  &.right { margin-left:  1.75em; }

  &.left,
  &.right {
    @include break(baby-bear) {
      width: auto;
      float: none;
      margin: 0 0 $baseMargin;
    }
  }

  &.block {
    width: auto;
    margin: $baseMargin 0;
  }

  ul {
    margin-bottom: -10px !important;
  }
}

.h-toc {
  margin: 0 0 10px;
  @include font-size(1.4);
  letter-spacing: 1px;
  text-transform: uppercase;
}


//
// Item.
//

.x-toc.block {
  &.two-columns {
    .x-toc-item {
      float: left;
      width: 48%;
      margin-right: 4%;

      &:nth-child(2n) { margin-right: 0; }
    }
  }

  &.three-columns {
    .x-toc-item {
      float: left;
      width: 30.66667%;
      margin-right: 4%;

      &:nth-child(3n) { margin-right: 0; }
    }
  }

  @include break(cubs) {
    &.three-columns .x-toc-item {
      width: 48%;

      &:nth-child(3n) { margin-right: 4%; }
      &:nth-child(2n) { margin-right: 0;  }
    }
  }

  @include break(baby-bear) {
    &.two-columns .x-toc-item,
    &.three-columns .x-toc-item {
      width: 100%;
      margin-right: 0;
    }
  }
}

.x-toc-item {
  margin-bottom: 10px;
  @include font-size(1.4);
  line-height: 1.3;

  a {
    display: block;
    border-bottom: 1px solid $shortcodeBorderColor;
    @include text-overflow();
  }
}



// Custom Headline
// =============================================================================

.h-custom-headline {
  letter-spacing: -1px;
  line-height: 1.1;

  &.accent {
    overflow: hidden;

    span {
      padding-bottom: 2px;
      display: inline-block;
      position: relative;

      &:before,
      &:after {
        content: "";
        position: absolute;
        top: 50%;
        height: 1px;
        width: 9999px;
        display: block;
        margin-top: -1px;
        background-color: $baseBorderSolid;
        background-color: $baseBorderRgba;
      }

      &:before {
        right: 100%;
        margin-right: 0.5em;
      }

      &:after {
        left: 100%;
        margin-left: 0.5em;
      }
    }
  }
}



// Feature Headline
// =============================================================================

.h-feature-headline {
  line-height: 1.1;

  span {
    display: inline-block;

    i {
      float: left;
      width: 2em;
      height: 2em;
      margin-right: 0.25em;
      font-size: 1em;
      line-height: 2em;
      text-align: center;
      color: $white;
      background-color: $headingsColor;
      border-radius: 100em;
    }
  }
}

h1,
.h1 {
  &.h-feature-headline span i {
    margin-top: -0.335em;
  }
}

h2,
.h2 {
  &.h-feature-headline span i {
    margin-top: -0.335em;
  }
}

h3,
.h3 {
  &.h-feature-headline span i {
    margin-top: -0.285em;
  }
}

h4,
.h4 {
  &.h-feature-headline span i {
    margin-top: -0.275em;
  }
}

h5,
.h5 {
  &.h-feature-headline span i {
    margin-top: -0.265em;
  }
}

h6,
.h6 {
  &.h-feature-headline span i {
    margin-top: -0.255em;
  }
}



// Search
// =============================================================================

.x-search-shortcode {  }



// Counter
// =============================================================================

.x-counter {  }



// Shortcode Container
// =============================================================================

.with-container {
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  padding: 2.5%;
  background-color: $baseModBackground;
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  // =============================================================================
// SCSS/SITE/STACKS/ICON/_POSTS-AND-PAGES.SCSS
// -----------------------------------------------------------------------------
// Styles for the site's posts and pages.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Main Containing Element
//   02. All Posts and Pages
//   03. Posts
//   04. Audio Posts
//   05. Image Posts
//   06. Gallery Posts
//   07. Quote Posts
//   08. Video Posts
//   09. Link Posts
//   10. Standard Posts
//   11. Portfolio Posts
//   12. Pages
//   13. Page Templates
//   14. 404 Page
// =============================================================================

// Main Containing Element
// =============================================================================

.x-main {
  position: relative;
  @include clearfix();

  .widget {
    margin-top: 3em;

    &:first-child {
      margin-top: 0;
    }
  }
}



// All Posts and Pages
// =============================================================================

.hentry,
.search-results .x-container > .product {
  .entry-wrap {
    position: relative;
    border-top: 1px solid $baseBorderSolid;
    border-top: 1px solid $baseBorderRgba;
    padding: 50px 0;
    @include clearfix();
    @include break(middle-bear) {
      padding: 35px 0;
    }
  }

  &:first-child {
    .entry-wrap {
      border-top: 0;
    }
  }

  &:last-child {
    .entry-wrap {
      border-bottom: 1px solid $baseBorderSolid;
      border-bottom: 1px solid $baseBorderRgba;
    }
  }

  p,
  ul,
  ol {
    &:last-child {
      margin-bottom: 0;
    }
  }
}

.single .hentry .entry-wrap {
  border-bottom: 1px solid $baseBorderSolid;
  border-bottom: 1px solid $baseBorderRgba;
}

.meta-comments {
  position: absolute;
  top: 10px;
  right: 10px;
  display: inline-block;
  width: 50px;
  height: 50px;
  margin: 0 auto;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  font-family: $altFontFamily;
  @include font-size(1.8);
  font-weight: 400;
  letter-spacing: 0;
  line-height: 48px;
  text-align: center;
  color: $black;
  color: rgba(0, 0, 0, 0.55);
  background-color: $white;
  border-radius: 100em;
  @include break(middle-bear) {
    width: 38px;
    height: 38px;
    @include font-size(1.4);
    line-height: 36px;
  }

  &:hover {
    color: rgba(0, 0, 0, 0.55);
  }
}

.entry-featured {
  position: relative;
  margin-top: 3%;
  border: 1px solid $baseBorderSolid;
  border: 1px solid $baseBorderRgba;
  padding: 7px;
  background-color: $baseModBackground;
}

.entry-thumb {
  display: block;
  position: relative;
  background-color: $baseModBackground;
  @include translate3d(0, 0, 0);

  img {
    min-width: 100%;
    @include transition(#{opacity 0.75s ease});
  }

  &:before {
    content: "\f0c1";
    display: block;
    position: absolute;
    margin: -36px 0 0 -35px;
    top: 50%;
    left: 50%;
    width: 70px;
    height: 70px;
    @include font-size(4.2);
    line-height: 72px;
    text-align: center;
    vertical-align: middle;
    color: $textColor;
    border-radius: 100em;
    @include opacity(0);
    @include transition(#{opacity 0.75s ease});
    @include font-awesome();
  }
}

a.entry-thumb:hover {
  img {
    @include opacity(0.35);
  }

  &:before {
    @include opacity(1);
  }
}

.entry-title {
  position: relative;
  width: 88%;
  margin: 0 auto;
  padding: 0 20px;
  font-size: 314%;
  line-height: 1;
  text-align: center;
  word-wrap: break-word;
  @include break(cubs)        { font-size: 257%; }
  @include break(middle-bear) { font-size: 200%; }
  @include break(baby-bear)   { font-size: 156%; }

  a {
    border: 0;
    @include opacity(1);

    &:hover {
      color: $headingsColor;
      @include opacity(0.65);
    }
  }

  &:before {
    position: relative;
    top: -0.125em;
    margin-right: 0.35em;
    font-size: 0.85em;
    line-height: 1;
    vertical-align: middle;
    @include opacity(0.5);
    @include font-awesome();
  }
}

.entry-title-sub {
  margin: 0.725em 0 0;
  font-size: 150%;
  font-weight: 300;
  color: $textColor;
  line-height: 1.05;
}

.p-meta {
  margin-top: 1%;
  font-size: 125%;
  line-height: 1;
  text-align: center;
  color: $headingsColor;
  @include opacity(0.35);
  @include break(middle-bear) {
    font-size: 112%;
  }

  > span {
    display: inline-block;
    margin: 0 1.75%;
  }
}

.entry-content {
  margin-top: 3%;
  padding: 0 10%;
  @include break(middle-bear) {
    padding: 0;
  }

  embed,
  iframe,
  object {
    width: 100%;
    max-width: 100%;
  }

  .more-link {
    line-height: 1.4;
  }
}

.entry-content.excerpt {
  p {
    margin-bottom: 0;
  }
}

.more-link {
  font-size: 135%;
  text-transform: uppercase;
}

.entry-footer {
  margin-top: 0.615em;

  a {
    @include btn_simple();
  }
}

.entry-sharing {
  @include font-size(1.6);
  line-height: 1;

  .entry-share {
    margin: 0 1.25%;
  }
}



// Posts
// =============================================================================

.x-iso-container-posts {
  &.cols-2 {
    .entry-title {
      font-size: 200%;
      @inclu