x-lost-password-input {
      margin-bottom: 0;

      + a {
        display: inline-block;
        margin-bottom: $baseMargin;
      }
    }
  }

  textarea {
    height: 6em;
  }

  .editfield {
    margin-bottom: $baseMargin;

    select,
    textarea,
    input[type="text"] {
      margin: 2px 0 5px;
    }

    p:last-child {
      margin-bottom: 0;
    }

    .description {
      margin: 0;
    }
  }

  .field-visibility-settings {
    display: none;
  }

  .field-visibility-settings-toggle {
    margin-bottom: 0;
  }

  .description {
    opacity: 0.65;
  }

  div.radio,
  div.checkbox {
    margin-bottom: $baseMargin;
  }

  div.radio {
    label {
      ul {
        margin-bottom: 10px;
      }

      &:last-child {
        ul {
          margin-bottom: 0;
        }
      }
    }
  }

  ul.radio {
    margin: 0;
  }
}



// Form: What's New
// =============================================================================

.buddypress.activity.directory #whats-new-form {
  margin-bottom: -$bpBorderWidth;
}

.buddypress #whats-new-form {
  margin-bottom: $bpSpacingSm;
  border: $bpBorder;
  padding: $bpSpacingSm;
  background-color: $bpAccentColor;
  @include box-shadow(#{$bpBoxShadowOuter});

  .activity-greeting {
    margin-bottom: $bpSpacingSm;
    font-size: 1.25em;
    line-height: 1;
  }

  textarea {
    margin: 0 0 2px;
    width: 100%;
    height: 6em;
  }

  #whats-new-options {
    display: none;
  }

  #x-whats-new-options-inner {
    padding: $bpSpacingSm 0 2px;
  }


  //
  // Submit button.
  //

  #whats-new-submit {
    float: left;
    width: 70px;
  }

  #aw-whats-new-submit {
    display: block;
    width: 100%;
    height: 2.65em;
    margin: 0;
    border: 0;
    padding: 0 0.65em;
    @include font-size(1.3);
    line-height: 1;
    text-shadow: none;
    color: $white;
    background-color: $accentColor;
    @include box-shadow(#{none});
    border-radius: $inputBorderRadius;

    &:focus,
    &:active {
      outline: 0;
    }
  }


  //
  // Submit select.
  //

  #whats-new-post-in-box {
    float: right;
    width: calc(100% - 85px);
  }

  #whats-new-post-in {
    width: 100%;
    margin: 0;

    &:focus,
    &:active {
      outline: 0;
    }
  }


  //
  // Form active state.
  //

  &.active {
    #whats-new-options {
      display: block;
    }
  }
}



// Form: Search Groups, Members, Messages, and Blogs
// =============================================================================

.buddypress {
  #search-groups-form,
  #search-members-form,
  #search-message-form,
  #blogs-directory-form {
    margin-bottom: -$bpBorderWidth;
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $bpAccentColor;
    @include box-shadow(#{$bpBoxShadowOuter});
    @include clearfix();

    input[type="submit"] {
      display: none;
    }

    label {
      margin: 0;
      width: 100%;

      input {
        margin: 0;
        width: 100%;
      }
    }
  }
}



// Item Header
// =============================================================================

.buddypress {
  #item-header {
    margin: 0 0 (-$bpBorderWidth);
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $bpAccentColor;
    @include box-shadow(#{$bpBoxShadowOuter});

    #message {
      float: left;
      width: 100%;
      margin: $bpSpacingSm 0 0;
    }
  }

  #item-header-avatar {
    float: left;
    width: 100px;
    text-align: center;

    > a {
      display: block;
      position: relative;

      > .highlight {
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 6px;
        @include font-size(0.9);
        letter-spacing: 1px;
        line-height: 1;
        text-transform: uppercase;
        color: $white;
        background-color: rgba(0, 0, 0, 0.35);
      }
    }

    .activity {
      display: block;
      margin: $bpSpacingSm 0 0;
      @include font-size(0.9);
      letter-spacing: 1px;
      line-height: 1.3;
      text-transform: uppercase;
    }
  }

  #item-header-content {
    float: right;
    width: calc(100% - 115px);
  }

  .x-item-header-title {
    margin: 0 0 10px;
    font-size: 165%;
    line-height: 1;
    text-transform: uppercase;
  }

  .user-nicename {
    margin: 0;
    font-size: 165%;
    line-height: 1.2;
    @include text-overflow();
  }

  #item-meta {
    p:last-of-type {
      margin-bottom: -2px;
    }
  }

  #latest-update {
    margin: 10px 0 -2px;
  }

  .item-action {
    margin: $bpSpacingSm 0 0;
    border-top: $bpBorder;

    > h3 {
      margin: 5px 0;
      float: left;
      @include font-size(0.9);
      letter-spacing: 1px;
      line-height: 1;
      text-transform: uppercase;
    }

    > ul {
      clear: both;
      margin: 0;
      list-style: none;
      @include clearfix();

      li {
        display: inline-block;
        width: 25px;
      }
    }
  }

  @include break(middle-bear) {
    #item-header-avatar,
    #latest-update {
      display: none;
    }

    #item-header-content {
      width: 100%;
    }
  }
}



// Navigation
// =============================================================================

.buddypress {
  .item-list-tabs {
    > ul {
      list-style: none;

      > li {
        display: inline-block;

        > a,
        > span {
          display: block;
          line-height: 1;
        }
      }
    }
  }

  .x-item-list-tabs-nav {
    margin: 0 0 $bpSpacingSm;
    border: $bpBorder;
    background-color: $bpAccentColor;
    @include box-shadow(#{$bpBoxShadowOuter, $bpBoxShadowInner});

    > ul {
      margin: 0 (-$bpBorderWidth) (-$bpBorderWidth) 0;
      @include clearfix();

      > li {
        width: 33.3333%;
        float: left;

        @include break(middle-bear) {
          width: 50%;
        }

        @include break(baby-bear) {
          float: none;
          width: 100%;
        }

        > a,
        > span {
          position: relative;
          border-right: $bpBorder;
          border-bottom: $bpBorder;
          padding: 10px 11px;
          color: $bpNavSubnavColor;
          background-color: $bpAccentColor;
          @include box-shadow(#{$bpBoxShadowInner});
        }

        > a:hover,
        &.current > a,
        &.selected > a, {
          background-color: $baseModBackground;
        }
      }
    }
  }

  .x-item-list-tabs-subnav {
    margin: 0 0 $bpSpacingSm;
    text-align: center;

    > ul {
      margin: 0;

      > li {
        > a {
          margin: 0 10px;
          color: $bpNavSubnavColor;
        }

        > a:hover,
        &.current > a {
          color: $accentColor;
        }

        &.groups-members-search {
          margin-top: (-$bpSpacingSm - $bpBorderWidth);
          width: 100%;
        }

        &.last {
          display: block;
          margin-top: $bpSpacingLg;

          label {
            display: none;
          }

          &.member-message-search {
            label {
              display: block;
            }
          }

          select {
            margin: 0;
            width: 100%;

            &:focus,
            &:active {
              outline: 0;
            }
          }
        }
      }
    }

    + #message,
    + .messages #message {
      margin: $bpSpacingLg 0 0;
    }
  }
}



// List: All Items
// =============================================================================

.buddypress .item-list {
  margin: 0;
  list-style: none;

  &#friend-list {
    margin-top: $bpSpacingLg;
  }

  > li {
    margin: 0 0 $bpSpacingSm;
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $baseModBackground;
    @include box-shadow(#{$bpBoxShadowOuter});
    @include clearfix();

    &:last-child {
      margin-bottom: 0;
    }
  }
}



// List: Activity
// =============================================================================

.buddypress .activity {
  #activity-loop-form {
    margin: 0;
  }
}

.buddypress .activity-list {

  //
  // Top level activity item.
  //

  > li {
    &.load-newest,
    &.load-more {
      padding: 0;

      > a {
        display: block;
        padding: $bpSpacingSm;
        line-height: 1;
        text-align: center;
      }
    }
  }

  .activity-content {
    clear: both;

    .activity-inner {
      margin-bottom: $bpSpacingSm;
    }
  }


  //
  // Replies to activity item.
  //

  .x-activity-comments-outer {
    clear: both;
    float: left;
    width: 100%;
    margin-bottom: (-$bpSpacingSm);
  }

  .activity-comments {
    margin: 15px (-$bpSpacingSm) 0;

    .ac-form {
      clear: both;
      margin: 0;
      border-top: $bpBorder;
      padding: $bpSpacingSm;
      background-color: $bpAccentColor;

      textarea {
        margin-bottom: $bpSpacingSm - 1px;
        width: 100%;
        height: 6em;
      }

      a,
      input[type="submit"] {
        display: block;
        float: right;
        margin: 0;
        border: 0;
        padding: 7px 12px;
        @include font-size(1.2);
        line-height: 1;
        text-shadow: none;
        color: $textColor;
        background-color: transparent;
        border-radius: 3px;
        @include box-shadow(#{none});

        &:focus,
        &:active {
          outline: 0;
        }
      }

      input[type="submit"] {
        color: $white;
        background-color: $accentColor;
      }
    }

    .ac-reply-avatar {
      float: left;
    }

    .ac-reply-content {
      float: right;
      width: calc(100% - #{$bpCalcSpacingLg});
    }

    > ul {
      float: right;
      width: 100%;
      margin: 0;
      background-color: $bpAccentColor;
      list-style: none;

      li {
        .x-acomment-content-wrap {
          margin: 0;
          border-top: $bpBorder;
          padding: $bpSpacingSm;
          @include font-size(1.2);
        }

        ul {
          margin: 0;
          list-style: none;

          li {
            margin: 0;
          }
        }
      }

      .acomment-avatar {
        float: left;
        width: 25px;
      }

      .acomment-meta,
      .acomment-content,
      .acomment-options {
        float: right;
        width: calc(100% - #{$bpCalcSpacingSm});
      }

      .acomment-meta {
        @include text-overflow();
      }

      .acomment-content {
        margin: 3px 0;
      }

      .acomment-options {
        > a {
          &:after {
            content: "\0020\2219\0020";
            color: $textColor;
            opacity: 0.65;
          }

          &:last-child:after {
            content: "";
          }
        }
      }
    }
  }
}



// List: Groups
// =============================================================================

.buddypress #groups-directory-form {
  margin: 0;
}

.buddypress {
  #group-list,
  #groups-list {
    &.invites {
      margin-top: $bpSpacingLg;
    }

    > li {
      .item-content {
        clear: both;

        .item-desc {
          margin-bottom: $bpSpacingSm;
        }
      }
    }
  }
}



// List: Blogs
// =============================================================================

.buddypress #blogs-list {
  .meta {
    max-width: 200px;
    @include text-overflow();
    @include break(middle-bear) {
      max-width: none;
    }
  }
}



// List Item: Header
// =============================================================================

.buddypress .x-list-item-header {
  margin-bottom: $bpSpacingSm;
  @include font-size(1.2);
  @include clearfix();

  .x-list-item-avatar-wrap {
    float: left;
    width: 45px;
  }

  .x-list-item-header-info {
    float: right;
    width: calc(100% - #{$bpCalcSpacingLg});
  }

  p {
    margin: 5px 0 0;
    line-height: 1.5;
    @include text-overflow();

    img {
      display: none;
    }
  }

  .activity,
  .time-since {
    display: block;
    color: $textColor;
    opacity: 0.5;
    @include text-overflow();
    @include transition(#{opacity 0.3s ease});
  }

  .time-since:hover {
    opacity: 0.75;
  }
}



// List Item: Meta
// =============================================================================

.buddypress .x-list-item-meta {
  clear: both;
  float: left;
  width: 100%;
  margin-bottom: (-$bpSpacingSm);
}

.buddypress .x-list-item-meta-inner {
  margin: 0 (-$bpSpacingSm);
  border-top: $bpBorder;
  @include clearfix();

  > a,
  > .generic-button {
    display: block;
    float: left;
    margin: 0;
  }

  > a,
  > .generic-button > a,
  > .meta {
    padding: 9px $bpSpacingSm;
    @include font-size(1.1);
    line-height: 1.2;
    text-align: center;
    color: $textColor;
  }

  > a,
  > .generic-button > a {
    display: block;
    float: left;
    position: relative;
    border-right: $bpBorder;
  }

  > .meta {
    float: right;
    border-left: $bpBorder;
    background-color: $bpAccentColor;

    > a {
      color: $textColor;
    }
  }

  @include break(middle-bear) {
    > a,
    > .generic-button {
      float: none;
      width: 100%;

      > a {
        float: none;
        width: 100%;
      }
    }

    > a,
    > .generic-button > a {
      border-right: 0;
      border-bottom: $bpBorder;
    }

    > a:last-child,
    > .generic-button:last-child > a {
      border-bottom: 0;
    }

    > .meta {
      float: none;
      width: 100%;
      border-left: 0;
      border-bottom: $bpBorder;

      &:last-child {
        border-bottom: 0;
      }
    }
  }
}



// BP Widget
// =============================================================================

.buddypress .bp-widget {
  h4 {
    margin-bottom: 10px;
    @include font-size(2.4);
    line-height: 1.3;
  }
}



// Button Navigation
// =============================================================================

.buddypress {

  //
  // All button navigation containers.
  //

  .button-nav,
  #item-buttons {
    @include clearfix();

    a {
      float: left;
      display: block;
      margin: 5px 5px 0 0;
      padding: 5px 6px;
      @include font-size(1.1);
      line-height: 1;
      text-align: center;
      color: $white;
      background-color: $accentColor;
      border-radius: 3px;
    }
  }


  //
  // Button navigation list.
  //

  .button-nav {
    margin: 0 0 $baseMargin;
    list-style: none;
  }

  h4 + .button-nav {
    margin-top: -5px;
  }


  //
  // Item header item buttons.
  //

  #item-buttons {
    margin-top: 9px;
  }
}



// Stand Alone Buttons
// =============================================================================

.buddypress .x-btn-bp {
  display: inline-block;
  margin: 0;
  padding: 5px 6px;
  @include font-size(1.1);
  line-height: 1;
  text-align: center;
  color: $white;
  background-color: $accentColor;
  border-radius: 3px;
}



// Pagination
// =============================================================================

.buddypress .pagination {
  margin-top: $bpSpacingSm;

  .pagination-links {
    display: inline-block;
    @include clearfix();
  }
}



// Tables
// =============================================================================

.buddypress .entry-content {
  table {
    border: $bpBorder;
    background-color: $baseModBackground;
    @include box-shadow(#{$bpBoxShadowOuter});

    th,
    td {
      border: $bpBorder;
    }
  }
}



// Admin Bar
/