
/**
 * Album View.
 *
 * @class
 *
 * @param {?object} options
 */
function AlbumView(options) {
  var self = this;
  var originalArtist;
  var response;

  /**
   * @prop {object}
   */
  this.options = _.isObject(options) ? options : {};

  /**
   * @prop {Ajax}
   */
  this.ajax = null;

  /**
   * @prop {Notifier}
   */
  this.notifier = new Notifier($('.alertBox'));
  /**
   * Initialize.
   *
   * @return {AlbumView}
   */
  this.init = function() {
    this.ajax = new Ajax();
    return this;
  };

  this.create = function() {
    return this.registerEventListeners();
  };

  this.registerEventListeners = function() {
    $(document).on('click', '.editArtist', function(event) {
      self.onEditArtistButtonClick($(this), event);
    });

    $(document).on('blur', '.artistSpan', function(event) {
        self.onArtistSpanBlur($(this));
    });

    return this;
  };

  /**
   * Edit artist button click event handler.
   *
   * @param {DOMElement} button
   * @param {Event}      event
   */
  this.onEditArtistButtonClick = function(button, event) {
    event.preventDefault();
    var artistSpan = button.prev('.artistSpan');
    this.makeArtistEditable(artistSpan, button);
  };

  this.makeArtistEditable = function(artistSpan, button) {
    button.hide();
    originalArtist = artistSpan.html();

    artistSpan
      .attr('contenteditable', true)
      .focus();
  };

  this.onArtistSpanBlur = function(artistSpan) {
    var editButton = artistSpan.next('.editArtist');
    this.updateArtist(artistSpan, editButton);
  }

  this.updateArtist = function(artistSpan, button) {
    if ('' === $.trim(artistSpan.html())) {

      this.notifier.info('Warning! Artist must NOT be empty!');

      self.keepOriginalArtist(artistSpan);
      return;
    }

    if (artistSpan.html() == originalArtist) {
      button.show();
      return;
    }

    var albumId = artistSpan.closest('.artistCell').data('id');

    this.notifier.loading('Updating artist...');

    this.ajax.post(
      'http://localhost:8080/album/updateartist',
      {
          artist: artistSpan.html(),
          id: albumId
      },
      function(respone) {
        response = 'Artis has been successfuly updated!';
        self.onUpdateArtistSuccess(response);
      },
      function(response) {
        response = 'Artis could not be updated!';
        self.onUpdateArtistError(response, artistSpan)
      }
    );

    this.onUpdateArtistSuccess = function(response) {
      self.notifier.success(response);
    }

    this.onUpdateArtistError = function(response, artistSpan) {
      self.keepOriginalArtist(artistSpan);
      self.notifier.error(response);
    }
  }

  this.keepOriginalArtist = function(artistSpan) {
    artistSpan.html(originalArtist);
    artistSpan.next().show();
  }

  // Initialize
  this.init();
}
