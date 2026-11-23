(function () {
  // 1. Slide Berita Baru (6 slides auto-play + thumb switcher)
  document.querySelectorAll(".js-rn-slider").forEach(function (slider) {
    var slides = slider.querySelectorAll(".rn-slide-item");
    var thumbs = slider.querySelectorAll(".rn-slide-thumb-btn");
    var prevBtn = slider.querySelector(".js-rn-prev");
    var nextBtn = slider.querySelector(".js-rn-next");
    if (!slides.length) return;

    var idx = 0;
    function showSlide(nextIdx) {
      idx = (nextIdx + slides.length) % slides.length;
      slides.forEach(function (el, i) {
        el.classList.toggle("is-active", i === idx);
      });
      thumbs.forEach(function (el, i) {
        el.classList.toggle("is-active", i === idx);
      });
    }

    thumbs.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var target = parseInt(btn.getAttribute("data-slide-index") || "0", 10);
        showSlide(target);
      });
    });

    if (prevBtn) prevBtn.addEventListener("click", function () { showSlide(idx - 1); });
    if (nextBtn) nextBtn.addEventListener("click", function () { showSlide(idx + 1); });

    setInterval(function () {
      showSlide(idx + 1);
    }, 5500);
  });

  // 2. Copy Link Media Share
  document.querySelectorAll(".js-rn-copy-link").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var url = btn.getAttribute("data-copy-url") || window.location.href;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url);
      }
      var label = btn.querySelector(".js-rn-copy-label");
      if (label) {
        var old = label.textContent;
        label.textContent = "Tersalin!";
        setTimeout(function () {
          label.textContent = old;
        }, 2000);
      } else {
        btn.style.backgroundColor = "#059669";
        setTimeout(function () {
          btn.style.backgroundColor = "";
        }, 1800);
      }
    });
  });

  // 3. YouTube Modal Player
  var ytModal = document.getElementById("rn-yt-modal");
  var ytIframe = document.getElementById("rn-yt-iframe");
  var ytTitle = document.getElementById("rn-yt-modal-title");
  var ytClose = document.getElementById("rn-yt-modal-close");

  document.querySelectorAll(".js-rn-play-yt").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var vid = btn.getAttribute("data-yt-id");
      var title = btn.getAttribute("data-yt-title") || "Video Berita";
      if (!vid || !ytModal || !ytIframe) return;
      if (ytTitle) ytTitle.textContent = title;
      ytIframe.src = "https://www.youtube.com/embed/" + encodeURIComponent(vid) + "?autoplay=1&rel=0";
      ytModal.style.display = "flex";
    });
  });

  function closeYtModal() {
    if (!ytModal || !ytIframe) return;
    ytIframe.src = "";
    ytModal.style.display = "none";
  }

  if (ytClose) ytClose.addEventListener("click", closeYtModal);
  if (ytModal) {
    ytModal.addEventListener("click", function (e) {
      if (e.target === ytModal) closeYtModal();
    });
  }
})();
