;(($) => {
  var wp = window.wp

  $(document).ready(() => {
    // Initialize color pickers
    $(".pcd-color-picker").wpColorPicker()

    // Handle logo upload
    var logoUploader

    $(".pcd-upload-logo").on("click", (e) => {
      e.preventDefault()

      if (logoUploader) {
        logoUploader.open()
        return
      }

      logoUploader = wp.media({
        title: "লোগো নির্বাচন করুন",
        button: {
          text: "এই লোগো ব্যবহার করুন",
        },
        multiple: false,
      })

      logoUploader.on("select", () => {
        var attachment = logoUploader.state().get("selection").first().toJSON()

        $("#watermark_logo").val(attachment.url)

        var $preview = $(".pcd-logo-preview")
        if ($preview.length === 0) {
          $preview = $('<div class="pcd-logo-preview"></div>').insertAfter(".pcd-upload-logo")
        }

        $preview.html('<img src="' + attachment.url + '" style="max-width: 200px; margin-top: 10px;">')
      })

      logoUploader.open()
    })

    var backgroundUploader

    $(".pcd-upload-background").on("click", (e) => {
      e.preventDefault()

      if (backgroundUploader) {
        backgroundUploader.open()
        return
      }

      backgroundUploader = wp.media({
        title: "ব্যাকগ্রাউন্ড ইমেজ নির্বাচন করুন",
        button: {
          text: "এই ইমেজ ব্যবহার করুন",
        },
        multiple: false,
      })

      backgroundUploader.on("select", () => {
        var attachment = backgroundUploader.state().get("selection").first().toJSON()

        $("#background_image").val(attachment.url)

        var $preview = $(".pcd-background-preview")
        if ($preview.length === 0) {
          $preview = $('<div class="pcd-background-preview"></div>')
            .insertAfter(".pcd-upload-background")
            .next(".pcd-toggle-label")
        }

        $preview.html('<img src="' + attachment.url + '" style="max-width: 300px; margin-top: 10px;">')
      })

      backgroundUploader.open()
    })

    $(".pcd-upload-ad-image").on("click", function (e) {
      e.preventDefault()
      var button = $(this)
      var targetInput = button.data("target")

      var mediaUploader = wp.media({
        title: "বিজ্ঞাপন ইমেজ নির্বাচন করুন",
        button: {
          text: "এই ইমেজ ব্যবহার করুন",
        },
        multiple: false,
      })

      mediaUploader.on("select", () => {
        var attachment = mediaUploader.state().get("selection").first().toJSON()
        $("#" + targetInput).val(attachment.url)

        // Show preview
        var previewContainer = button.parent().find(".pcd-ad-preview")
        if (previewContainer.length === 0) {
          previewContainer = $('<div class="pcd-ad-preview" style="margin-top: 10px;"></div>')
          button.parent().append(previewContainer)
        }
        previewContainer.html(
          '<img src="' +
            attachment.url +
            '" alt="Ad Preview" style="max-width: 300px; height: auto; border: 1px solid #ddd; border-radius: 4px;">',
        )
      })

      mediaUploader.open()
    })

    $("#title_font_family").on("change", function () {
      var fontFamily = $(this).val()
      var fontStack =
        "'" +
        fontFamily +
        "', 'Noto Sans Bengali', 'Hind Siliguri', Arial, sans-serif"

      var $titleElement = $("#pcd-adjustable-title")
      if ($titleElement.length > 0) {
        $titleElement.css("font-family", fontStack)
      }
    })

    $("#title_border_radius").on("change", function () {
      var borderRadius = $(this).val()
      var $titleElement = $("#pcd-adjustable-title")
      if ($titleElement.length > 0) {
        $titleElement.css("border-radius", borderRadius + "px")
      }
    })

    $("#default_font_size").on("change", function () {
      var fontSize = $(this).val()
      var $titleElement = $("#pcd-adjustable-title")
      if ($titleElement.length > 0) {
        $titleElement.css("font-size", fontSize + "px")
      }
    })

    $("#default_line_height").on("change", function () {
      var lineHeight = $(this).val()
      var $titleElement = $("#pcd-adjustable-title")
      if ($titleElement.length > 0) {
        $titleElement.css("line-height", lineHeight)
      }
    })
  })
})(jQuery)
