<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ config('app.name') }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>{{ file_get_contents(public_path('assets/pdf.css')) }}</style>
</head>
<body>
  <div style="padding-top: 50px"></div>

  {{-- Table --}}
  <table class="table table-items">
    <thead>
      <tr>
        <th scope="col" class="border-0 pl-0">
          name
        </th>
        <th scope="col" class="text-center border-0">
          qty
        </th>
        <th scope="col" class="text-right border-0">
          price
        </th>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td class="pl-0">
            <strong>product 1</strong>
          </td>
          <td class="text-center">2</td>
          <td class="text-right">
            59
          </td>
        </tr>
        <tr>
          <td class="pl-0">
            <strong>product 2</strong>
          </td>
          <td class="text-center">4</td>
          <td class="text-right">
            48
          </td>
        </tr>
        <tr>
          <td class="pl-0">
            <strong>product 3</strong>
          </td>
          <td class="text-center">7</td>
          <td class="text-right">
            36
          </td>
        </tr>
    </tbody>
  </table>
</body>
</html>