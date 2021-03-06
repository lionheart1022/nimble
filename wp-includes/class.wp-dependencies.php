
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

    th,
    td {
      border: $bpBorder;
    }
  }
}



// Admin Bar
// =============================================================================

#wpadminbar .quicklinks li#wp-admin-bar-user-admin-with-avatar > a img,
#wpadminbar .quicklinks li#wp-admin-bar-group-admin-with-avatar > a img {
  display: inline;
  float: none;
  width: 16px;
  height: 16px;
  margin: -2px 10px 0 -5px;
  border: 1px solid #999;
  padding: 0;
  background: #eee;
  vertical-align: middle;
}

#wpadminbar .quicklinks li#wp-admin-bar-group-admin-with-avatar ul {
  left: 0;
}

#wpadminbar .quicklinks li#wp-admin-bar-group-admin-with-avatar ul ul {
  left: 0;
}

#wpadminbar .quicklinks li#wp-admin-bar-my-account a span.count,
#wpadminbar .quicklinks li#wp-admin-bar-my-account-with-avatar a span.count,
#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications {
  display: inline;
  padding: 2px 5px;
  color: $white;
  background: #21759b;
  font-size: 10px;
  font-weight: bold;
  text-shadow: none;
  border-radius: 10px;
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications {
  background: #ddd;
  color: #333;
  margin: 0
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications.alert {
  color: $white;
  background-color: #1fb3dd;
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications > a {
  padding: 0 0.5em;
}

#wp-admin-bar-user-info img.avatar{
  width: 64px;
  height: 64px;
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             