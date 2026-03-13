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

    // ===== TITLE STYLE STATE =====
    const $title = $("#pcd-adjustable-title")
    const baseFontWeight = $title.css("font-weight") || "700"
    const baseFontStyle = $title.css("font-style") || "normal"

    let isBold = false
    let isItalic = false
    let lineStyleState = []

    function normalizeColor(color) {
      if (!color) return "#ffffff"
      const value = String(color).trim()

      if (/^#([0-9a-f]{3})$/i.test(value)) {
        return "#" + value.substring(1).split("").map((c) => c + c).join("").toLowerCase()
      }

      if (/^#([0-9a-f]{6})$/i.test(value)) {
        return value.toLowerCase()
      }

      const rgbMatch = value.match(/^rgba?\((\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i)
      if (rgbMatch) {
        const toHex = (num) => {
          const clamped = Math.max(0, Math.min(255, Number.parseInt(num, 10) || 0))
          return clamped.toString(16).padStart(2, "0")
        }
        return `#${toHex(rgbMatch[1])}${toHex(rgbMatch[2])}${toHex(rgbMatch[3])}`
      }

      return "#ffffff"
    }

    function getTitleLines() {
      const raw = ($("#pcd-title-editor").val() || "").replace(/\r/g, "")
      const lines = raw.split("\n")
      return lines.length ? lines : [""]
    }

    function getCurrentTitleStyles() {
      return {
        fontSize: $title.css("font-size"),
        lineHeight: $title.css("line-height"),
        fontFamily: $title.css("font-family"),
        fontWeight: isBold ? "900" : baseFontWeight,
        fontStyle: isItalic ? "italic" : baseFontStyle,
      }
    }

    function ensureLineState(lines) {
      const defaultColor = normalizeColor($title.css("color"))
      lineStyleState = lines.map((_, index) => {
        const previous = lineStyleState[index] || {}
        return {
          color: normalizeColor(previous.color || defaultColor),
          bold: !!previous.bold,
          italic: !!previous.italic,
        }
      })
    }

    function setLineButtonState($button, isActive) {
      $button.toggleClass("active", isActive)
      $button.css({
        background: isActive ? "#667eea" : "#f1f5f9",
        color: isActive ? "#fff" : "#000",
      })
    }

    function renderTitleFromLineState() {
      const lines = getTitleLines()
      ensureLineState(lines)
      const styles = getCurrentTitleStyles()

      let html = ""
      lines.forEach((line, index) => {
        const lineState = lineStyleState[index]
        const text = line.length ? $("<span>").text(line).html() : "&nbsp;"
        html +=
          '<span data-line="' +
          index +
          '" style="display:block;color:' +
          lineState.color +
          ';' +
          (lineState.bold ? "font-weight:900;" : "") +
          (lineState.italic ? "font-style:italic;" : "") +
          '">' +
          text +
          "</span>"
      })

      $title.css({
        "white-space": "pre-line",
        "font-size": styles.fontSize,
        "line-height": styles.lineHeight,
        "font-family": styles.fontFamily,
        "font-weight": styles.fontWeight,
        "font-style": styles.fontStyle,
      })

      $title.html(html)
    }

    function updateLineColorInputs() {
      const lines = getTitleLines()
      ensureLineState(lines)
      const $container = $("#pcd-line-colors-container")
      $container.empty()

      lines.forEach((line, index) => {
        const state = lineStyleState[index]
        const shortLine = line.trim().length === 0 ? "(খালি লাইন)" : (line.length > 22 ? line.substring(0, 22) + "..." : line)

        const $row = $('<div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;"></div>')
        const $color = $('<input type="color" class="pcd-line-color" style="width:30px;height:26px;padding:0;border:1px solid #e2e8f0;border-radius:4px;cursor:pointer;">')
          .attr("data-line", index)
          .val(state.color)

        const $bold = $('<button type="button" class="pcd-line-bold-btn" style="padding:2px 8px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer;font-weight:900;font-size:12px;" title="Bold">B</button>')
          .attr("data-line", index)

        const $italic = $('<button type="button" class="pcd-line-italic-btn" style="padding:2px 8px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer;font-style:italic;font-size:12px;" title="Italic">I</button>')
          .attr("data-line", index)

        const $label = $('<span style="font-size:11px;color:#64748b;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></span>')
          .text(`লাইন ${index + 1}: ${shortLine}`)

        setLineButtonState($bold, state.bold)
        setLineButtonState($italic, state.italic)

        $row.append($color, $bold, $italic, $label)
        $container.append($row)
      })
    }

    // ===== FONT SIZE SLIDER =====
    $("#pcd-font-size-slider").on("input", function () {
      const fontSize = $(this).val()
      $title.css("font-size", fontSize + "px")
      $("#pcd-font-size-value").text(fontSize + "px")
      renderTitleFromLineState()
    })

    // ===== LINE HEIGHT SLIDER =====
    $("#pcd-line-height-slider").on("input", function () {
      const lineHeight = $(this).val()
      $title.css("line-height", lineHeight)
      $("#pcd-line-height-value").text(lineHeight)
      renderTitleFromLineState()
    })

    // ===== TITLE ALIGNMENT =====
    $(document).on("click", ".pcd-align-btn", function (e) {
      e.preventDefault()
      e.stopPropagation()
      const align = $(this).data("align")
      $(".pcd-align-btn").removeClass("active")
      $(this).addClass("active")
      $title.css("text-align", align)
      return false
    })

    // ===== BOLD / ITALIC (global) =====
    $("#pcd-bold-btn").on("click", function (e) {
      e.preventDefault()
      isBold = !isBold
      $(this).toggleClass("active", isBold)
      $title.css("font-weight", isBold ? "900" : baseFontWeight)
      renderTitleFromLineState()
    })

    $("#pcd-italic-btn").on("click", function (e) {
      e.preventDefault()
      isItalic = !isItalic
      $(this).toggleClass("active", isItalic)
      $title.css("font-style", isItalic ? "italic" : baseFontStyle)
      renderTitleFromLineState()
    })

    // ===== FONT SELECTOR =====
    $("#pcd-font-selector").on("change", function () {
      const font = $(this).val()
      const fontStack = "'" + font + "', 'Noto Sans Bengali', 'SolaimanLipi', 'Kalpurush', sans-serif"
      $title.css("font-family", fontStack)

      $(".pcd-photocard").find("[style*='font-family']").each(function() {
        if (!$(this).is("#pcd-adjustable-title") && !$(this).closest("#pcd-adjustable-title").length) {
          $(this).css("font-family", fontStack)
        }
      })

      renderTitleFromLineState()
    })

    // ===== TITLE TEXT EDITOR =====
    $("#pcd-title-editor").on("input", function () {
      updateLineColorInputs()
      renderTitleFromLineState()
    })

    // ===== LINE-WISE COLOR + BOLD/ITALIC SYSTEM =====
    $(document).on("input change", ".pcd-line-color", function() {
      const lineIndex = Number.parseInt($(this).attr("data-line"), 10)
      if (Number.isNaN(lineIndex) || !lineStyleState[lineIndex]) return
      lineStyleState[lineIndex].color = normalizeColor($(this).val())
      renderTitleFromLineState()
    })

    $(document).on("click", ".pcd-line-bold-btn", function(e) {
      e.preventDefault()
      const lineIndex = Number.parseInt($(this).attr("data-line"), 10)
      if (Number.isNaN(lineIndex) || !lineStyleState[lineIndex]) return
      lineStyleState[lineIndex].bold = !lineStyleState[lineIndex].bold
      setLineButtonState($(this), lineStyleState[lineIndex].bold)
      renderTitleFromLineState()
    })

    $(document).on("click", ".pcd-line-italic-btn", function(e) {
      e.preventDefault()
      const lineIndex = Number.parseInt($(this).attr("data-line"), 10)
      if (Number.isNaN(lineIndex) || !lineStyleState[lineIndex]) return
      lineStyleState[lineIndex].italic = !lineStyleState[lineIndex].italic
      setLineButtonState($(this), lineStyleState[lineIndex].italic)
      renderTitleFromLineState()
    })

    $("#pcd-apply-line-colors").on("click", function (e) {
      e.preventDefault()
      renderTitleFromLineState()
    })

    updateLineColorInputs()
    renderTitleFromLineState()

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

      const currentScale = $card.css("--pcd-scale") || "0.55"
      const currentWrapperHeight = $wrapper.css("height")

      $card.css({
        "--pcd-scale": "1",
        "transform": "scale(1)"
      })
      $wrapper.css({
        "height": "1080px",
        "overflow": "visible"
      })

      await new Promise(r => setTimeout(r, 300))

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
