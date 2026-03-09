document.addEventListener("DOMContentLoaded", async () => {
  const panoramas = document.querySelectorAll("#bppiv_product_panorama");


  panoramas.forEach((container) => {
    // get elements
    const summeryElem = document.querySelector(".summary.entry-summary"); // product summery
    if (summeryElem && container.parentElement.classList.contains("woocommerce-product-gallery")) {
      container.style.height = container.offsetWidth > summeryElem.offsetHeight ? `${summeryElem.offsetHeight}px` : `${container.offsetWidth}px`; //`600px`;
    } else {
      container.style.height = `${container.offsetWidth * 0.5}px`;
    }

    const settings = jsonParse(container.dataset.settings) || {};
    const { image_src, initialView, autoRotate, title, author, showControls, video_src, type, video360, video_show_controls, video_autoplay, video_mute, video_loop } = settings;
    container.removeAttribute("data-settings");

    if (type === "video" && Boolean(parseInt(video360))) {
      const videoeSource = video_src || [];

      const panoramaVideo = new PANOLENS.VideoPanorama(videoeSource, {
        autoplay: Boolean(parseInt(video_autoplay)),
        loop: Boolean(parseInt(video_loop)),
        muted: Boolean(parseInt(video_mute)),
      });

      const panoramaViewer = new PANOLENS.Viewer({
        container: container,
        controlBar: Boolean(parseInt(video_show_controls)),
      });
      panoramaViewer.add(panoramaVideo);
    } else if (type === "image") {
      const options = {
        type: "equirectangular",
        panorama: image_src,
        autoLoad: true,
        autoRotate: Boolean(parseInt(autoRotate)),
        pitch: parseInt(initialView.top),
        yaw: parseInt(initialView.right),
        hfov: parseInt(initialView.bottom),
        showControls: Boolean(parseInt(showControls)),
      };

      if (title) {
        options.title = title;
      }
      if (author) {
        options.author = author;
      }

      pannellum.viewer(container, options);
    }
  });
});

function jsonParse(json) {
  try {
    return JSON.parse(json);
  } catch (error) {
    console.warn(error.message);
  }
}

const getMeta = (url, cb) => {
  const img = new Image();
  img.onload = () => cb(null, img);
  img.onerror = (err) => cb(err);
  img.src = url;
};
