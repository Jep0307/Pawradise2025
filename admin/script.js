const ctx = document.getElementById("myChart").getContext("2d");

const myChart = new Chart(ctx, {
  type: "line",
  data: {
    labels: ["Red", "Blue", "Yellow"],
    datasets: [
      {
        label: "# of Votes",
        data: [12, 19, 3],
        backgroundColor: ["red", "blue", "yellow"],
      },
    ],
  },
});

const data = {
  labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6"],
  datasets: [
    {
      label: "Dataset",
      data: Utils.numbers({ count: 6, min: -100, max: 100 }),
      borderColor: Utils.CHART_COLORS.red,
      backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
      pointStyle: "circle",
      pointRadius: 10,
      pointHoverRadius: 15,
    },
  ],
};

function closePopup() {
  const popup = document.querySelector(".popup-overlay");
  if (popup) {
    popup.classList.remove("show");
  }
}

window.addEventListener("load", function () {
  const popup = document.querySelector(".popup-overlay");
  if (popup) {
    setTimeout(() => {
      closePopup();
    }, 3000);
  }
});
