<div class="mb-3">

    <label class="form-label">
        Username
    </label>

    <input
        type="text"
        name="username"
        value="{{ old('username', $user->username ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        First Name
    </label>

    <input
        type="text"
        name="firstname"
        value="{{ old('firstname', $user->firstname ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Last Name
    </label>

    <input
        type="text"
        name="lastname"
        value="{{ old('lastname', $user->lastname ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Email
    </label>

    <input
        type="email"
        name="email"
        value="{{ old('email', $user->email ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Password
    </label>

    <input
        type="password"
        name="password"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Status
    </label>

    <select
        name="is_active"
        class="form-select">

        <option value="1"
            @selected(old('is_active', $user->is_active ?? 1))>
            Active
        </option>

        <option value="0"
            @selected(old('is_active', $user->is_active ?? 1) == 0)>
            Inactive
        </option>

    </select>

</div>

<div class="mb-3">

    <label class="form-label">
        Role
    </label>

    <select
        name="is_admin"
        class="form-select">

        <option value="0">
            User
        </option>

        <option value="1">
            Admin
        </option>

    </select>

</div>

<button
    type="submit"
    class="btn btn-primary">

    Save

</button>