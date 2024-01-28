<script>
    import InputText from "$lib/components/inputText.svelte";
    import InputEmail from "$lib/components/inputemail.svelte";
    import InputPassword from "$lib/components/inputpassword.svelte";
    let username = '';
    let email = '';
    let password = '';
    let confirmPassword = '';

    

</script>
  
<style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-image: linear-gradient(rgba(62, 112, 65, 0.75),rgba(160, 119, 119, 0.75));
    }

    .signup-form {
        background-color: #333;;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    .signup-form h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .btn {
        width: 100%;
        background-color: rgb(15, 189, 15);
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 3px;
        cursor: pointer;
    }

    .login-link {
        text-align: center;
        margin-top: 15px;
    }

    .login-link a {
        color: rgb(15, 189, 15);
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
</style>
  
  <div class="container">
    <form action="/submit_signup" method="post" class="signup-form">
      <h2>Sign Up</h2>
      
      <!-- Username Input -->
      <InputText id="username"  val={username} labelName="Username"/>
      
      <!-- Email Input -->
      <InputEmail id="email"  val={email} labelName="Email"/>
      
      <!-- Password Input -->
      <InputPassword id="password"  val={password} labelName="Password"/>
      
      <!-- Confirm Password Input -->
      <InputPassword id="confirm_password" val={confirmPassword} labelName="Confirm-Password:"/>
      
      <!-- Signup Button -->
      <input type="submit" class="btn" value="Sign Up">
      
      <!-- Login Link -->
      <p class="login-link">Already have an account? <a href="/login">Login</a></p>
    </form>
  </div>

  