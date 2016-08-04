<?php use Framework\Template as T; ?>
<table>
  <tbody>
    <tr>
      <th>ユーザID</th>
      <td>
        <input type="text" name="user_name" value="<?php echo T::h($user_name); ?>" />
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <input type="password" name="password" value="<?php echo T::h($password); ?>" />
      </td>
    </tr>
  </tbody>
</table>
