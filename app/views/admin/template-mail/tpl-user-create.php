<table class="table_full editable-bg-color bg_color_e6e6e6 editable-bg-image" bgcolor="#ebf8fd" width="100%"
       align="center" mc:repeatable="castellab" mc:variant="Header" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>
            <table class="table1 editable-bg-color bg_color_303f9f" width="600" align="center" border="0"
                   cellspacing="0" cellpadding="0" style="margin: 0 auto; background: url(<?= HOST . ASSETS ?>images/header.jpg);background-size: contain;">
                <tr>
                    <td height="25"></td>
                </tr>
                <tr>
                    <td>
                        <table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0"
                               style="margin: 0 auto;">
                            <tr>
                                <td>
                                    <table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="left">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="60"></td>
                            </tr>

                            <tr>
                                <td align="center" class="text_color_ffffff"
                                    style="color: #ffffff; font-size: 30px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
                              <span class="text_container">
                                 <multiline style="color: #ffbb43">
                                 <?= $sujet ?>
                                 </multiline>
                              </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td height="30"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="104"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table1 editable-bg-color bg_color_ffffff" bgcolor="#ffffff" width="600" align="center"
                   border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                <tr>
                    <td height="60"></td>
                </tr>
                <tr>
                    <td>
                        <table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0"
                               style="margin: 0 auto;">
                            <tr>
                                <td mc:edit="text011" align="left" class="center_content text_color_282828"
                                    style="color: #282828; font-size: 20px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
                              <span class="text_container">
                                 <multiline>
                                    <?= $this->lang["bjrcher"] ." ". $nom_client ?> ,
                                 </multiline>
                              </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td height="10"></td>
                            </tr>
                            <tr>
                                <td align="left" class="center_content text_color_a1a2a5"
                                    style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
                              <span class="text_container">
                                 <multiline>
                                     <?= $contenue ?>
                                     <br/>
                                 </multiline>
                              </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td align="left" class="center_content text_color_a1a2a5"
                                    style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
                              <span class="text_container">
                                 <multiline>
                                    Merci
                                 </multiline>
                              </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td height="5"></td>
                            </tr>
                            <tr>
                                <td align="left" class="center_content text_color_a1a2a5"
                                    style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
                              <span class="text_container">
                                 <multiline>
                                 </multiline>
                              </span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="60"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

