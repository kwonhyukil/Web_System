document.addEventListener("DOMContentLoaded", () => {
  // 로그인 버튼과 회원가입 버튼 선택
  const loginButton = document.querySelector(".login-button:first-of-type"); // 첫 번째 버튼
  const signupButton = document.querySelector(".login-button:last-of-type"); // 두 번째 버튼

  // 로그인 버튼 클릭 시 login.html로 이동
  if (loginButton) {
    loginButton.addEventListener("click", () => {
      console.log("로그인 버튼 클릭 이벤트 발생!");
      window.location.href = "login.html"; // 로그인 페이지로 이동
    });
  } else {
    console.error("로그인 버튼을 찾을 수 없습니다.");
  }

  // 회원가입 버튼 클릭 시 signup.html로 이동
  if (signupButton) {
    signupButton.addEventListener("click", () => {
      console.log("회원가입 버튼 클릭 이벤트 발생!");
      window.location.href = "signup.html"; // 회원가입 페이지로 이동
    });
  } else {
    console.error("회원가입 버튼을 찾을 수 없습니다.");
  }
});
