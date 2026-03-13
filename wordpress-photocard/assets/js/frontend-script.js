;(($) => {
  const html2canvas = window.html2canvas

  $(document).ready(() => {
    if ($(".pcd-editor-page").length === 0) return

    // ===== DYNAMIC SCALING =====
    function updatePhotocardScale() {
      const $wrapper = $(".pcd-photocard-wrapper")
      const $card = $(".pcd-photocard-with-border")
      if (!$wrapper.length || !$card.length) return

      const wrapperWidth = $wrapper.width()
      const cardWidth = 1080
      const scale = Math.min(wrapperWidth / cardWidth, 1)
      const scaledHeight = cardWidth * scale

      $card.css("--pcd-scale", scale)
      $wrapper.css("height", scaledHeight + "px")
    }

    updatePhotocardScale()
    $(window).on("resize", updatePhotocardScale)

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

    // ===== BOLD / ITALIC =====
    var isBold = false
    var isItalic = false

    $("#pcd-bold-btn").on("click", function (e) {
      e.preventDefault()
      isBold = !isBold
      $(this).toggleClass("active", isBold)
      $("#pcd-adjustable-title").css("font-weight", isBold ? "900" : "700")
    })

    $("#pcd-italic-btn").on("click", function (e) {
      e.preventDefault()
      isItalic = !isItalic
      $(this).toggleClass("active", isItalic)
      $("#pcd-adjustable-title").css("font-style", isItalic ? "italic" : "normal")
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
          '<p style="font-size: 12px; color: #94a3b8; margin: 0;">একাধিক লাইন লিখলে প্রতিটি লাইনের জন্য আলাদা কালার সেট করতে পারবেন।</p>'
        )
        return
      }

      lines.forEach((line, index) => {
        const shortLine = line.length > 20 ? line.substring(0, 20) + "..." : line
        container.append(
          '<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">' +
            '<input type="color" class="pcd-line-color" data-line="' + index + '" value="#FFD700" style="width: 30px; height: 26px; padding: 0; border: 1px solid #e2e8f0; border-radius: 4px; cursor: pointer;">' +
            '<span style="font-size: 12px; color: #64748b; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">লাইন ' + (index + 1) + ': ' + shortLine + '</span>' +
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
        const color = colorInput.length > 0 ? colorInput.val() : "#ffffff"
        const fontWeight = isBold ? "font-weight: 900;" : ""
        const fontStyle = isItalic ? "font-style: italic;" : ""
        html += '<span style="color: ' + color + '; ' + fontWeight + fontStyle + '">' + $("<span>").text(line).html() + "</span><br>"
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
        $btn.html('<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>')
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

      const $card = $(".pcd-photocard-with-border")
      const $wrapper = $(".pcd-photocard-wrapper")
      const el = $card[0]
      const quality = Number.parseInt($(".pcd-photocard").attr("data-quality")) || 4

      // Save current scale state
      const currentScale = $card.css("--pcd-scale") || "0.55"
      const currentWrapperHeight = $wrapper.css("height")

      // Reset to full size for capture
      $card.css({
        "--pcd-scale": "1",
        "transform": "scale(1)"
      })
      $wrapper.css({
        "height": "1080px",
        "overflow": "visible"
      })

      // Wait for reflow
      await new Promise(r => setTimeout(r, 150))

      const canvas = await html2canvas(el, {
        scale: quality,
        useCORS: true,
        allowTaint: false,
        backgroundColor: null,
        logging: false,
        imageTimeout: 0,
        removeContainer: true,
        width: 1080,
        height: 1080,
        windowWidth: 1080,
        windowHeight: 1080,
      })

      // Restore scale
      $card.css({
        "--pcd-scale": currentScale,
        "transform": "scale(var(--pcd-scale, 0.55))"
      })
      $wrapper.css({
        "height": currentWrapperHeight,
        "overflow": "visible"
      })

      return canvas
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
      const originalText = $button.html()

      $button.prop("disabled", true)
      $button.html('⬇️ ডাউনলোড হচ্ছে... <span class="pcd-loading"></span>')

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
            $button.html(originalText)
            alert("ফটোকার্ড সফলভাবে ডাউনলোড হয়েছে!")
          }, "image/png")
        })
        .catch((error) => {
          console.error("Error:", error)
          $credit.css("display", creditDisplay)
          $button.prop("disabled", false)
          $button.html(originalText)
          alert("ফটোকার্ড তৈরি করতে সমস্যা হয়েছে।")
        })
    })
  })
})(jQuery)
