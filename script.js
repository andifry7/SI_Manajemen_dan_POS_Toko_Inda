function progress() {
  const bar = document.getElementById("loadingBar");
  let width = 0;

  const loading = setInterval(() => {
    if (width < 80) {
      width += 2;
    } else if (width < 95) {
      width += 1;
    } else {
      width += 0.2;
    }

    bar.style.width = width + "%";

    if (width >= 100) {
      clearInterval(loading);
      setTimeout(() => {
        window.location.href = "auth/login.php";
      }, 300);
    }
  }, 30);
}

progress();
