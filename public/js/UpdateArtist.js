"use strict";

var artist,
    id,
    originalArtist;

function makeEditable(artistElement, albumId) {
    artist = artistElement;
    id = albumId;
    originalArtist = artist.innerHTML;

    artist.setAttribute("contenteditable", "true");
    artist.focus();

    callUpdateArtistAction(artist);
};

function callUpdateArtistAction(artist) {
    artist.addEventListener('blur', function() {
        updateArtist(this);
    });
}

function updateArtist(artist) {
    if (!artist.innerHTML.length) {
        window.location.href = '/album';
        return;
    }

    if (artist.innerHTML == originalArtist) {
      window.location.href = '/album';
      return;
    }

    $.ajax('http://localhost:8080/album/updateartist', {
        data: {
            artist: artist.innerHTML,
            id: id
        },
        type: "post",
        success: function(){
            //TODO
        }
    });
}
