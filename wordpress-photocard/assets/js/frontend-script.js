;(($) => {
  const html2canvas = window.html2canvas

  $(document).ready(() => {
    if ($(".pcd-editor-page").length > 0) {
      // Font size slider
      $("#pcd-font-size-slider").on("input", function () {
        const fontSize = $(this).val()
        const $title = $("#pcd-adjustable-title")
        $title.css({
          "font-size": fontSize + "px",
        })
        $("#pcd-font-size-value").text(fontSize + "px")
      })

      // Line height slider
      $("#pcd-line-height-slider").on("input", function () {
        const lineHeight = $(this).val()
        const $title = $("#pcd-adjustable-title")
        $title.css({
          "line-height": lineHeight,
        })
        $("#pcd-line-height-value").text(lineHeight)
      })

      // FIX: NEW - Title Editor (editable title text)
      $("#pcd-title-editor").on("input", function () {
        const newTitle = $(this).val()
        const $title = $("#pcd-adjustable-title")
        // Preserve HTML spans for line colors if they exist
        if ($title.find("span").length > 0) {
          // Reset to plain text when editing
          $title.text(newTitle)
          updateLineColorInputs()
        } else {
          $title.text(newTitle)
          updateLineColorInputs()
        }
      })

      // FIX: NEW - Line-wise color system
      function updateLineColorInputs() {
        const titleText = $("#pcd-title-editor").val()
        const lines = titleText.split("\n").filter((l) => l.trim() !== "")
        const container = $("#pcd-line-colors-container")
        container.empty()

        if (lines.length <= 1) {
          container.html(
            '<p style="font-size: 12px; color: #888;">একাধিক লাইন লিখলে প্রতিটি লাইনের জন্য আলাদা কালার সেট করতে পারবেন।</p>',
          )
          return
        }

        lines.forEach((line, index) => {
          const shortLine = line.length > 25 ? line.substring(0, 25) + "..." : line
          container.append(
            '<div style="display: flex; align-items: center; gap: 8px;">' +
              '<input type="color" class="pcd-line-color" data-line="' +
              index +
              '" value="#FFD700" style="width: 32px; height: 28px; padding: 0; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">' +
              '<span style="font-size: 12px; color: #555; flex: 1;">লাইন ' +
              (index + 1) +
              ': ' +
              shortLine +
              "</span>" +
              "</div>",
          )
        })
      }

      // Initialize line color inputs
      updateLineColorInputs()

      // Apply line colors
      $("#pcd-apply-line-colors").on("click", function () {
        const titleText = $("#pcd-title-editor").val()
        const lines = titleText.split("\n").filter((l) => l.trim() !== "")
        const $title = $("#pcd-adjustable-title")

        if (lines.length <= 1) {
          return
        }

        let html = ""
        lines.forEach((line, index) => {
          const colorInput = $(`.pcd-line-color[data-line="${index}"]`)
          const color = colorInput.length > 0 ? colorInput.val() : "#000000"
          html += '<span style="color: ' + color + ';">' + $("<span>").text(line).html() + "</span><br>"
        })

        $title.html(html)
      })

      // Copy link button
      $("#pcd-copy-link-button").on("click", function (e) {
        e.preventDefault()
        const permalink = window.pcdPostPermalink

        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard
            .writeText(permalink)
            .then(() => {
              const $btn = $(this)
              const originalHtml = $btn.html()
              $btn.css({
                background: "#10b981",
                color: "white",
                transform: "scale(1.05)",
              })
              $btn.html(
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
              )
              setTimeout(() => {
                $btn.html(originalHtml)
                $btn.css({
                  background: "",
                  color: "",
                  transform: "",
                })
              }, 2000)
            })
            .catch(() => {
              alert("লিংক কপি করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।")
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
            const $btn = $(this)
            const originalHtml = $btn.html()
            $btn.css({
              background: "#10b981",
              color: "white",
              transform: "scale(1.05)",
            })
            $btn.html(
              '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
            )
            setTimeout(() => {
              $btn.html(originalHtml)
              $btn.css({
                background: "",
                color: "",
                transform: "",
              })
            }, 2000)
          } catch (err) {
            alert("লিংক কপি করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।")
          }
          document.body.removeChild(textArea)
        }
      })

      async function capturePhotocard() {
        // Wait for all fonts to be loaded
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

              let platformName = ""
              let shareUrl = ""

              switch (platform) {
                case "facebook":
                  platformName = "Facebook"
                  shareUrl = "https://www.facebook.com/"
                  break
                case "twitter":
                  platformName = "Twitter"
                  shareUrl = "https://twitter.com/compose/tweet"
                  break
                case "whatsapp":
                  platformName = "WhatsApp"
                  shareUrl = "https://web.whatsapp.com/"
                  break
                case "linkedin":
                  platformName = "LinkedIn"
                  shareUrl = "https://www.linkedin.com/feed/"
                  break
                case "instagram":
                  platformName = "Instagram"
                  shareUrl = "https://www.instagram.com/"
                  break
              }

              setTimeout(() => {
                alert(
                  `ফটোকার্ড ডাউনলোড সম্পন্ন হয়েছে!\n\nএখন ${platformName} খুলছে। সেখানে ডাউনলোড করা ফটোকার্ড ইমেজটি আপলোড করে শেয়ার করুন।`,
                )
                if (shareUrl) {
                  window.open(shareUrl, "_blank")
                }
              }, 500)
            }, "image/png")
          })
          .catch((error) => {
            console.error("Error generating photocard:", error)
            $credit.css("display", creditDisplay)
            $button.prop("disabled", false)
            $button.html(originalHtml)
            alert("ফটোকার্ড তৈরি করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।")
          })
      }

      $("#pcd-share-facebook").on("click", (e) => {
        e.preventDefault()
        sharePhotocardImage("facebook")
      })

      $("#pcd-share-twitter").on("click", (e) => {
        e.preventDefault()
        sharePhotocardImage("twitter")
      })

      $("#pcd-share-whatsapp").on("click", (e) => {
        e.preventDefault()
        sharePhotocardImage("whatsapp")
      })

      $("#pcd-share-instagram").on("click", (e) => {
        e.preventDefault()
        sharePhotocardImage("instagram")
      })

      $("#pcd-share-linkedin").on("click", (e) => {
        e.preventDefault()
        sharePhotocardImage("linkedin")
      })

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
              const filename = "photocard-" + Date.now() + ".png"

              link.href = url
              link.download = filename
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
            console.error("Error generating photocard:", error)

            $credit.css("display", creditDisplay)

            $button.prop("disabled", false)
            $button.text(originalText)

            alert("ফটোকার্ড তৈরি করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।")
          })
      })
    }
  })
})(jQuery)
