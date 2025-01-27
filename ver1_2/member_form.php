<script>
    function check_input()
    {
        if (!document.member_form.id.value){
            alert("아이디를 입력하세요!");
            document.member_form.id.focus();
            return;
        }

        if (!document.member_form.pass.value){
            alert("비밀번호를 입력하세요!");
            document.member_form.pass.focus();
            return;
        }

        if (!document.member_form.pass_confirm.value){
            alert("비밀번호 확인을 입력하세요!");
            document.member_form.pass_confirm.focus();
            return;
        }

        if (!document.member_form.name.value){
            alert("이름을 입력하세요!");
            document.member_form.name.focus();
            return;
        }

        if (!document.member_form.email1.value){
            alert("이메일 주소를 입력하세요!");
            document.member_form.email1.focus();
            return;
        }

        if (!document.member_form.email2.value){
            alert("이메일 주소를 입력하세요!");
            document.member_form.email2.focus();
            return;
        }

        if (document.member_form.pass.value){
            alert("비밀번호가 일치하지 않습니다. \n 다시 입력해 주세요!");
            document.member_form.focus();
            document.member_form.pass.select();
            return;
        }

        document.member_form.sumit();
    }

    function reset_form() {
        document.member_form.id.value = "";
        document.member_form.pass.value = "";
        document.member_form.pass_confirm.value = "";
        document.member_form.name.value = "";
        document.member_form.email1.value = "";
        document.member_form.email2.value = "";
        document.member_form.form.id.focus();
        return;
    }
    
    fuction check_id() {
        window.open("member_check_id.php? id=" + document.member_form.
        id.value, "IDcheck",
        "left=700,top=300,width=350,height=200,scrollbars=no,resizable=yes");
    }

</script>

<div id = "join_box">
    <form name = "member_form" method = "post" action = "member_insert.php">
        <h2>회원 가입<h2>
        <div class = "form id">
            <div class = "col1">아이디</div>
            <div class = "col2">
                <input type= "text" name = "id">
            </div>
            <idv class = "col3">
                <a href= "#"Ximg src= "./
