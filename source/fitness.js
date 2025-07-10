const muscleData = {
    chest: [
      { img: "img/fitness/nguc/barbellbenchpress.png", caption: "Barbell Bench Press" },
      { img: "img/fitness/nguc/chestfly.png", caption: "Chest Fly" },
      { img: "img/fitness/nguc/declinebenchpress.png", caption: "Decline Bench Press" },
      { img: "img/fitness/nguc/dip.png", caption: "Chest Dips" },
      { img: "img/fitness/nguc/dumbbellbenchpress.png", caption: "Dumbbell Bench Press" },
      { img: "img/fitness/nguc/dumbbellpull-over.png", caption: "Dumbbell Pullover" },
      { img: "img/fitness/nguc/inclinebenchpress.png", caption: "Incline Bench Press" },
      { img: "img/fitness/nguc/machinechestpress.png", caption: "Machine Chest Press" },
      { img: "img/fitness/nguc/machinefly.png", caption: "Machine Fly" },
      { img: "img/fitness/nguc/pushup.png", caption: "Push-up" }
    ],
    back: [
      { img: "img/fitness/lung/deadlift.png", caption: "Deadlift" },
      { img: "img/fitness/lung/dumbbellrow.png", caption: "Dumbbell Row" },
      { img: "img/fitness/lung/bentoverdumbbell.png", caption: "Bent Over Dumbbell" },
      { img: "img/fitness/lung/dumbellrenegaderow.png", caption: "Dumbell Renegade Row" },
      { img: "img/fitness/lung/seatedcablerow.png", caption: "Seated Cable Row" },
      { img: "img/fitness/lung/latpulldown.png", caption: "Lat Pulldown" },
      { img: "img/fitness/lung/pullup.png", caption: "Pull Up" },
      { img: "img/fitness/lung/chinup.png", caption: "Chin Up" },
      { img: "img/fitness/lung/australianpullups.png", caption: "Australian Pull Ups" },
      { img: "img/fitness/lung/pendlayrow.png", caption: "Pendlay Row" }
    ],
    shoulders: [
      { img: "img/fitness/vai/dayta.png", caption: "Smith Machine Over Head Shoulder Press" },
      { img: "img/fitness/vai/keocap.png", caption: "Cable Upright Row" },
      { img: "img/fitness/vai/nangvai.png", caption: "Front Cable Right" },
      { img: "img/fitness/vai/ngoidayvai.png", caption: "Arnold Dumbbell Press" },
      { img: "img/fitness/vai/dungkeocap.png", caption: "Cable Rope Rear Delts Row" },
      { img: "img/fitness/vai/vaisau.png", caption: "Dumbbell Rear Lateral Raise" },
      { img: "img/fitness/vai/vocanh.png", caption: "Side Lateral Rise" },
      { img: "img/fitness/vai/vaitruoc.png", caption: "Dumbbell Shoulder Press" },
      { img: "img/fitness/vai/nangta.png", caption: "Barbell Shoulder Press" }
    ],
    arms: [
    { img: "img/fitness/tay/Bicep Curl.png", caption: "Bicep Curl" },
    { img: "img/fitness/tay/Chin-up.png", caption: "Chin-up" },
    { img: "img/fitness/tay/Close-grip Push-up.png", caption: "Close-grip Push-up" },
    { img: "img/fitness/tay/Concentration Curl.png", caption: "Concentration Curl" },
    { img: "img/fitness/tay/Dips.png", caption: "Dips" }, // Lưu ý có khoảng trắng sau Dips
    { img: "img/fitness/tay/Hammer Curl.png", caption: "Hammer Curl" },
    { img: "img/fitness/tay/Plank.png", caption: "Plank" }, // Plank thường là bài tập core, không phải tay chính
    { img: "img/fitness/tay/Push-up.png", caption: "Push-up" }, // Push-up tác động ngực, vai, tay sau
    { img: "img/fitness/tay/Tricep Extension.png", caption: "Tricep Extension" },
    { img: "img/fitness/tay/Wrist Curl.png", caption: "Wrist Curl" }
    ],
    legs: [
    { img: "img/fitness/chan/Bodyweight Squat.png", caption: "Bodyweight Squat" },
    { img: "img/fitness/chan/Inner Thigh Leg Raises.png", caption: "Inner Thigh Leg Raises" },
    { img: "img/fitness/chan/Side Lunges.png", caption: "Side Lunges" },
    { img: "img/fitness/chan/Squat Jumps.png", caption: "Squat Jumps" }
    ],
    abs: [
    { img: "img/fitness/bung/Bridge Lift.png", caption: "Bridge Lift" },
    { img: "img/fitness/bung/Scissor kick.png", caption: "Scissor Kick" },
    { img: "img/fitness/bung/Side Plank.png", caption: "Side Plank" },
    { img: "img/fitness/bung/The extended plank.png", caption: "The Extended Plank" },
    { img: "img/fitness/bung/The Jackknife sit up.png", caption: "The Jackknife Sit-up" },
    { img: "img/fitness/bung/The long-arm crunch.png", caption: "The Long-arm Crunch" },
    { img: "img/fitness/bung/The reverse crunch.png", caption: "The Reverse Crunch" }
    ]
};

document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Scroll animation cho .fitness-intro
  const section = document.querySelector(".fitness-intro");
  const observer1 = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        section.classList.add("show");
        observer.unobserve(section);
      }
    });
  }, { threshold: 0.2 });

  if (section) observer1.observe(section);

  // --- 2. Delay animation cho .goal-box
  const boxes = document.querySelectorAll(".goal-box");
  const observer2 = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        entry.target.style.transitionDelay = `${index * 0.2}s`;
        entry.target.classList.add("show");
        observer2.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  boxes.forEach(box => observer2.observe(box));

  // --- 3. Slide nhóm cơ

  let currentIndex = 0;
  let currentGroup = "";

  const sliderBox = document.getElementById("slider-box");
  const slideImg = document.getElementById("slide-img");
  const slideCaption = document.getElementById("slide-caption");

  document.querySelectorAll(".muscle-card").forEach(card => {
    card.addEventListener("click", () => {
      const group = card.dataset.target;
      console.log("Bạn đã click:", group);
      if (!muscleData[group]){ 
        console.log("Không có dữ liệu nhóm:", group);
        return;}

      currentGroup = group;
      currentIndex = 0;
      updateSlide();
      sliderBox.classList.remove("hidden");
      sliderBox.classList.add("show");
    });
  });

  function updateSlide() {
    const data = muscleData[currentGroup][currentIndex];
    console.log("Slide:", data);
    slideImg.src = data.img;
    slideCaption.textContent = data.caption;
}


  document.getElementById("prev").addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + muscleData[currentGroup].length) % muscleData[currentGroup].length;
    updateSlide();
  });

  document.getElementById("next").addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % muscleData[currentGroup].length;
    updateSlide();
  });

  document.querySelector(".close-btn").addEventListener("click", () => {
    sliderBox.classList.remove("show");
    sliderBox.classList.add("hidden");
  });


});

document.addEventListener("DOMContentLoaded", function () {
  const cards = document.querySelectorAll(".muscle-card");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        entry.target.style.animationDelay = `${index * 0.15}s`;
        entry.target.classList.add("show");
        observer.unobserve(entry.target); // chỉ chạy 1 lần
      }
    });
  }, {
    threshold: 0.3
  });

  cards.forEach(card => observer.observe(card));
});


document.addEventListener("DOMContentLoaded", () => {
  const burnSection = document.querySelector(".burn-fat-section");
  const videoCards = document.querySelectorAll(".video-card");

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Hiện cả section
        burnSection.classList.add("show");

        // Từng card thêm hiệu ứng với delay
        videoCards.forEach((card, index) => {
          setTimeout(() => {
            card.classList.add("fade-in");
          }, index * 200); // delay 200ms mỗi card
        });

        observer.unobserve(entry.target); // chỉ 1 lần
      }
    });
  }, {
    threshold: 0.5
  });

  if (burnSection) {
    observer.observe(burnSection);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Thay đổi selector từ ".dangerous-food" sang ".nutrition-and-exercise-balance"
  const section = document.querySelector(".nutrition-and-exercise-balance");

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        section.classList.add("show");
        section.classList.remove("hidden");
        obs.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.2 // Ngưỡng hiển thị 20% của phần tử để kích hoạt animation
  });

  // Đảm bảo rằng phần tử section tồn tại trước khi quan sát
  if (section) observer.observe(section);
});

document.getElementById("calo-form").addEventListener("submit", function(e) {
    e.preventDefault();

    const caloIn = parseInt(document.getElementById("calo-in").value);
    const duration = parseInt(document.getElementById("duration").value);
    const caloOut = duration * 7; // tiêu hao 7 calo mỗi phút

    fetch("save_calo.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `calo_in=${caloIn}&calo_out=${caloOut}`
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("calo-result").innerHTML = data;
    });
});