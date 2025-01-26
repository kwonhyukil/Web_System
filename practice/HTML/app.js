const express = require("express");
const session = require("express-session");

const app = express();

// 세션 설정
app.use(
  session({
    secret: "your_secret_key", // 세션 암호화 키
    resave: false,
    saveUninitialized: true,
    cookie: {
      httpOnly: true, // JavaScript 접근 차단
      secure: false, // HTTPS에서만 전송 (테스트 환경에서는 false로 설정)
    },
  })
);

// 쿠키 설정
app.get("/set-cookie", (req, res) => {
  res.cookie("userId", "12345", {
    maxAge: 60 * 60 * 1000, // 1시간 (밀리초 단위)
    httpOnly: true, // JavaScript 접근 차단
  });
  res.send("쿠키가 설정되었습니다!");
});

// 세션 설정
app.get("/set-session", (req, res) => {
  req.session.username = "john_doe"; // 세션 데이터 저장
  res.send("세션이 설정되었습니다!");
});

app.listen(3000, () => {
  console.log("Server is running on http://localhost:3000");
});
