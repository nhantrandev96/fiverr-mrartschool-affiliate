(function($) {
  defaults = {
    formDataKey: "files",
    buttonText: "Add Files",
    buttonClass: "file-preview-button",
    shadowClass: "file-preview-shadow",
    tableCss: "file-preview-table",
    tableRowClass: "file-preview-row",
    placeholderClass: "file-preview-placeholder",
    loadingCss: "file-preview-loading",
    tableTemplate: function() {
      return "<table class='table table-striped file-preview-table' id='file-preview-table'>" +
	       "<tbody></tbody>" +
	     "</table>";
    },
    rowTemplate: function(options) {
      return "<tr class='" + config.tableRowClass + "'>" +
	       "<td>" + "<img src='" + options.src + "' class='" + options.placeholderCssClass + "' />" + "</td>" +
	       "<td class='filename'>" + options.name + "</td>" +
	       "<td class='filesize'>" + options.size + "</td>" +
	       "<td class='remove-file'><button class='btn btn-danger'>&times;</button></td>" +
	     "</tr>";
    },
    loadingTemplate: function() {
      return "<div id='file-preview-loading-container'>" +
	       "<div id='"+config.loadingCss+"' class='loader-inner ball-clip-rotate-pulse no-show'>" +
		 "<div></div>" +
		 "<div></div>" +
	       "</div>" +
	     "</div>";
    }
  }

  //NOTE: Depends on Humanize-plus (humanize.js)
  if(typeof Humanize == 'undefined' || typeof Humanize.filesize != 'function'){
    $.getScript("https://cdnjs.cloudflare.com/ajax/libs/humanize-plus/1.5.0/humanize.min.js")
  }

  var getFileSize = function(filesize) {
    return Humanize.fileSize(filesize);
  };

  // NOTE: Ensure a required filetype is matching a MIME type
  // (partial match is fine) and not matching against file extensions.
  //
  // Quick ref:  http://www.sitepoint.com/web-foundations/mime-types-complete-list/
  //
  // NOTE: For extended support of mime types, we should use https://github.com/broofa/node-mime
  var getFileTypeCssClass = function(filetype) {
    var fileTypeCssClass;
    fileTypeCssClass = (function() {
      switch (true) {
	case /video/.test(filetype):
	  return 'video';
	case /audio/.test(filetype):
	  return 'audio';
	case /pdf/.test(filetype):
	  return 'pdf';
	case /csv|excel/.test(filetype):
	  return 'spreadsheet';
	case /powerpoint/.test(filetype):
	  return 'powerpoint';
	case /msword|text/.test(filetype):
	  return 'document';
	case /zip/.test(filetype):
	  return 'zip';
	case /rar/.test(filetype):
	  return 'rar';
	default:
	  return 'default-filetype';
      }
    })();
    return defaults.placeholderClass + " " + fileTypeCssClass;
  };

  $.fn.uploadPreviewer = function(options, callback) {
    var that = this;

    if (!options) {
      options = {};
    }
    config = $.extend({}, defaults, options);
    var buttonText,
	previewRowTemplate,
	previewTable,
	previewTableBody,
	previewTableIdentifier,
	currentFileList = [];

    if (window.File && window.FileReader && window.FileList && window.Blob) {

      this.wrap("<span class='btn btn-primary " + config.shadowClass + "'></span>");
      buttonText = this.parent("." + config.shadowClass);
      buttonText.prepend("<span>" + config.buttonText + "</span>");
      buttonText.wrap("<span class='" + config.buttonClass + "'></span>");

      previewTableIdentifier = options.preview_table;
      if (!previewTableIdentifier) {
	$("span." + config.buttonClass).after(config.tableTemplate());
	previewTableIdentifier = "table." + config.tableCss;
      }

      previewTable = $(previewTableIdentifier);
      previewTable.addClass(config.tableCss);
      previewTableBody = previewTable.find("tbody");

      previewRowTemplate = options.preview_row_template || config.rowTemplate;

      previewTable.after(config.loadingTemplate());

      previewTable.on("click", ".remove-file", function() {
	var parentRow = $(this).parent("tr");
	var filename = parentRow.find(".filename").text();
	for (var i = 0; i < currentFileList.length; i++) {
	  if (currentFileList[i].name == filename) {
	    currentFileList.splice(i, 1);
	    break;
	  }
	}
	parentRow.remove();
	$.event.trigger({ type: 'file-preview:changed', files: currentFileList });
      });

      this.on('change', function(e) {
	var loadingSpinner = $("#" + config.loadingCss);
	loadingSpinner.show();

	var reader;
	var filesCount = e.currentTarget.files.length;
	$.each(e.currentTarget.files, function(index, file) {
	  currentFileList.push(file);

	  reader = new FileReader();
	  reader.onload = function(fileReaderEvent) {
	    var filesize, filetype, imagePreviewRow, placeholderCssClass, source;
	    if (previewTableBody) {
	      filetype = file.type;
	      if (/image/.test(filetype)) {
		source = fileReaderEvent.target.result;
		placeholderCssClass = config.placeholderClass + " image";
	      } else {
		source = "";
		placeholderCssClass = getFileTypeCssClass(filetype);
	      }
	      filesize = getFileSize(file.size);
	      imagePreviewRow = previewRowTemplate({
		src: source,
		name: file.name,
		placeholderCssClass: placeholderCssClass,
		size: filesize
	      });

	      previewTableBody.append(imagePreviewRow);

	      if (index == filesCount - 1) {
		loadingSpinner.hide();
	      }
	    }
	    if (callback) {
	      callback(fileReaderEvent);
	    }
	  };
	  reader.readAsDataURL(file);
	});

	$.event.trigger({ type: 'file-preview:changed', files: currentFileList });
      });

      this.fileList = function() {
	return currentFileList;
      }

      this.clearFileList = function() {
	$('.remove-file').click();
      }

      this.url = function(url) {
	if (url != undefined) {
	  config.url = url;
	} else {
	  return config.url;
	}
      }

      this._onComplete = function(eventData) {
	eventData['type'] = 'file-preview:submit:complete'
	$.event.trigger(eventData);
      }

      this.submit = function(successCallback, errorCallback) {
	if (config.url == undefined) throw('Please set the URL to which I shall post the files');

	if (currentFileList.length > 0) {
	  var filesFormData = new FormData();
	  currentFileList.forEach(function(file) {
	    filesFormData.append(options.formDataKey + "[]", file);
	  });

	  $.ajax({
	    type: "POST",
	    url: config.url,
	    data: filesFormData,
	    contentType: false,
	    processData: false,
	    xhr: function() {
	      var xhr = new window.XMLHttpRequest();
	      xhr.upload.addEventListener("progress", function(evt) {
		if (evt.lengthComputable &&
		    options != null &&
		    options.uploadProgress != null
		    && typeof options.uploadProgress == "function") {
		  options.uploadProgress(evt.loaded / evt.total);
		}
	      }, false);
	      return xhr;
	    },
	    success: function(data, status, jqXHR) {
	      if (typeof successCallback == "function") {
		successCallback(data, status, jqXHR);
	      }
	      that._onComplete({ data: data, status: status, jqXHR: jqXHR });
	    },
	    error: function(jqXHR, status, error) {
	      if (typeof errorCallback == "function") {
		errorCallback(jqXHR, status, error);
	      }
	      that._onComplete({ error: error, status: status, jqXHR: jqXHR });
	    }
	  });
	} else {
	  console.log("There are no selected files, please select at least one file before submitting.");
	  that._onComplete({ status: 'no-files' });
	}
      }

      return this;

    } else {
      throw "The File APIs are not fully supported in this browser.";
    }
  };
})(jQuery);
