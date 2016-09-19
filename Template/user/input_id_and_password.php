<?php use Framework\Template as T; ?>
<table>
  <tbody>
    <tr>
      <th>ユーザID</th>
      <td>
        <input type="text" name="user_name" autofocus value="<?php echo T::h($user_name); ?>" required pattern="[a-zA-Z0-9_\-]{4,20}" title="半角英数とハイフンが使えます。4〜20文字で入力して下さい。" />
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <input type="password" name="password" value="<?php echo T::h($password); ?>" required pattern="[a-zA-Z0-9_!@#$%^&~`\\|:;<>{}\-]{8,20}" title="半角英数と !@#$%^&amp;~`\|:;&lt;&gt;{}- が使えます。8〜20文字で入力して下さい。" />
      </td>
    </tr>
  </tbody>
</table>
