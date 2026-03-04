;(($) => {
  const html2canvas = window.html2canvas

  $(document).ready(() => {
    if ($(".pcd-editor-page").length > 0) {
      
      // ===== FONT SIZE SLIDER =====
      $("#pcd-font-size-slider").on("input", function () {
        const fontSize = $(this).val()
        $("#pcd-adjustable-title").css("font-size", fontSize + "px")
        $("#pcd-font-size-value").text(fontSize + "px")
      })

      // ===== LINE HEIGHT SLIDER =====
      $("#pcd-line-height-slider").on("input", function () {
        const lineHeight = $(this).val()
        $("#pcd-adjustable-title").css("line-height", lineHeight)
        $("#pcd-line-height-value").text(lineHeight)
      })

      // ===== TITLE ALIGNMENT =====
      $(document).on("click", ".pcd-align-btn", function (e) {
        e.preventDefault()
        e.stopPropagation()
        const align = $(this).data("align")
        $(".pcd-align-btn").removeClass("active")
        $(this).addClass("active")
        $("#pcd-adjustable-title").css("text-align", align)
        return false
      })

      // ===== TITLE TEXT EDITOR =====
      $("#pcd-title-editor").on("input", function () {
        const newTitle = $(this).val()
        $("#pcd-adjustable-title").text(newTitle)
        updateLineColorInputs()
      })

      // ===== LINE-WISE COLOR SYSTEM =====
      function updateLineColorInputs() {
        const titleText = $("#pcd-title-editor").val()
        const lines = titleText.split("\n").filter((l) => l.trim() !== "")
        const container = $("#pcd-line-colors-container")
        container.empty()

        if (lines.length <= 1) {
          container.html(
            '<p style="font-size: 12px; color: #888;">একাধিক লাইন লিখলে প্রতিটি লাইনের জন্য আলাদা কালার সেট করতে পারবেন।</p>'
          )
          return
        }

        lines.forEach((line, index) => {
          const shortLine = line.length > 25 ? line.substring(0, 25) + "..." : line
          container.append(
            '<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">' +
              '<input type="color" class="pcd-line-color" data-line="' + index + '" value="#FFD700" style="width: 32px; height: 28px; padding: 0; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">' +
              '<span style="font-size: 12px; color: #555; flex: 1;">লাইন ' + (index + 1) + ': ' + shortLine + '</span>' +
            '</div>'
          )
        })
      }

      updateLineColorInputs()

      // ===== APPLY LINE COLORS =====
      $("#pcd-apply-line-colors").on("click", function () {
        const titleText = $("#pcd-title-editor").val()
        const lines = titleText.split("\n").filter((l) => l.trim() !== "")
        const $title = $("#pcd-adjustable-title")

        if (lines.length <= 1) return

        let html = ""
        lines.forEach((line, index) => {
          const colorInput = $(`.pcd-line-color[data-line="${index}"]`)
          const color = colorInput.length > 0 ? colorInput.val() : "#000000"
          html += '<span style="color: ' + color + ';">' + $("<span>").text(line).html() + "</span><br>"
        })

        $title.html(html)
      })

      // ===== COPY LINK =====
      $("#pcd-copy-link-button").on("click", function (e) {
        e.preventDefault()
        const permalink = window.pcdPostPermalink
        const $btn = $(this)
        const originalHtml = $btn.html()

        function showSuccess() {
          $btn.css({ background: "#10b981", color: "white", transform: "scale(1.05)" })
          $btn.html('<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>')
          setTimeout(() => {
            $btn.html(originalHtml)
            $btn.css({ background: "", color: "", transform: "" })
          }, 2000)
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(permalink).then(showSuccess).catch(() => {
            alert("লিংক কপি করতে সমস্যা হয়েছে।")
          })
        } else {
          const textArea = document.createElement("textarea")
          textArea.value = permalink
          textArea.style.position = "fixed"
          textArea.style.left = "-999999px"
          document.body.appendChild(textArea)
          textArea.select()
          try {
            document.execCommand("copy")
            showSuccess()
          } catch (err) {
            alert("লিংক কপি করতে সমস্যা হয়েছে।")
          }
          document.body.removeChild(textArea)
        }
      })

      // ===== CAPTURE PHOTOCARD =====
      async function capturePhotocard() {
        if (document.fonts && document.fonts.ready) {
          await document.fonts.ready
        }

        const $photocardWrapper = $(".pcd-photocard-with-border")[0]
        const quality = Number.parseInt($(".pcd-photocard").attr("data-quality")) || 4

        return html2canvas($photocardWrapper, {
          scale: quality,
          useCORS: true,
          allowTaint: false,
          backgroundColor: null,
          logging: false,
          imageTimeout: 0,
          removeContainer: true,
          windowWidth: 1080,
          windowHeight: 1080,
        })
      }

      // ===== SHARE =====
      function sharePhotocardImage(platform) {
        const $button = $(`#pcd-share-${platform}`)
        const originalHtml = $button.html()

        $button.prop("disabled", true)
        $button.html('<span class="pcd-loading"></span>')

        const $credit = $(".pcd-footer-credit")
        const creditDisplay = $credit.css("display")
        $credit.hide()

        capturePhotocard()
          .then((canvas) => {
            $credit.css("display", creditDisplay)
            canvas.toBlob((blob) => {
              const blobUrl = URL.createObjectURL(blob)
              const link = document.createElement("a")
              link.href = blobUrl
              link.download = "photocard-" + Date.now() + ".png"
              document.body.appendChild(link)
              link.click()
              document.body.removeChild(link)
              URL.revokeObjectURL(blobUrl)

              $button.prop("disabled", false)
              $button.html(originalHtml)

              const platforms = {
                facebook: { name: "Facebook", url: "https://www.facebook.com/" },
                twitter: { name: "Twitter", url: "https://twitter.com/compose/tweet" },
                whatsapp: { name: "WhatsApp", url: "https://web.whatsapp.com/" },
                linkedin: { name: "LinkedIn", url: "https://www.linkedin.com/feed/" },
                instagram: { name: "Instagram", url: "https://www.instagram.com/" },
              }

              const p = platforms[platform]
              if (p) {
                setTimeout(() => {
                  alert(`ফটোকার্ড ডাউনলোড সম্পন্ন!\n\n${p.name} খুলছে। ডাউনলোড করা ইমেজটি আপলোড করে শেয়ার করুন।`)
                  window.open(p.url, "_blank")
                }, 500)
              }
            }, "image/png")
          })
          .catch((error) => {
            console.error("Error:", error)
            $credit.css("display", creditDisplay)
            $button.prop("disabled", false)
            $button.html(originalHtml)
            alert("ফটোকার্ড তৈরি করতে সমস্যা হয়েছে।")
          })
      }

      $("#pcd-share-facebook").on("click", (e) => { e.preventDefault(); sharePhotocardImage("facebook") })
      $("#pcd-share-twitter").on("click", (e) => { e.preventDefault(); sharePhotocardImage("twitter") })
      $("#pcd-share-whatsapp").on("click", (e) => { e.preventDefault(); sharePhotocardImage("whatsapp") })
      $("#pcd-share-instagram").on("click", (e) => { e.preventDefault(); sharePhotocardImage("instagram") })
      $("#pcd-share-linkedin").on("click", (e) => { e.preventDefault(); sharePhotocardImage("linkedin") })

      // ===== DOWNLOAD =====
      $("#pcd-download-button").on("click", function (e) {
        e.preventDefault()
        const $button = $(this)
        const originalText = $button.text()

        $button.prop("disabled", true)
        $button.html('ডাউনলোড হচ্ছে... <span class="pcd-loading"></span>')

        const $credit = $(".pcd-footer-credit")
        const creditDisplay = $credit.css("display")
        $credit.hide()

        capturePhotocard()
          .then((canvas) => {
            $credit.css("display", creditDisplay)
            canvas.toBlob((blob) => {
              const url = URL.createObjectURL(blob)
              const link = document.createElement("a")
              link.href = url
              link.download = "photocard-" + Date.now() + ".png"
              document.body.appendChild(link)
              link.click()
              document.body.removeChild(link)
              URL.revokeObjectURL(url)

              $button.prop("disabled", false)
              $button.text(originalText)
              alert("ফটোকার্ড সফলভাবে ডাউনলোড হয়েছে!")
            }, "image/png")
          })
          .catch((error) => {
            console.error("Error:", error)
            $credit.css("display", creditDisplay)
            $button.prop("disabled", false)
            $button.text(originalText)
            alert("ফটোকার্ড তৈরি করতে সমস্যা হয়েছে।")
          })
      })
    }
  })
})(jQuery)
