document.addEventListener("DOMContentLoaded", function () {
  const toggles = document.querySelectorAll(".transparencia-toggle");

  toggles.forEach(function (toggle) {
    toggle.addEventListener("click", function () {
      const item = this.closest(".transparencia-item");
      const icon = this.closest(".transparencia-icon");

      item.classList.toggle("active");
      icon.classList.toggle("active");
    });
  });
});
