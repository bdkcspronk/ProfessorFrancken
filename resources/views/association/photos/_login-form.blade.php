<form action="{{ action([\Francken\Association\Photos\Http\Controllers\AuthenticationController::class, 'store']) }}"
      class="album-login__form"
      method="post"
>
    @csrf
    <label class="sr-only" for="password">Album password</label>
    <input name="password" type="password" class="form-control" id="password" placeholder="Album password" required>
    <button type="submit" class="btn home-btn home-btn--crimson">Login</button>
</form>
