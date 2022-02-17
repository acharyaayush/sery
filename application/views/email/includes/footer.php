                                     

                                    <tr>
                                        <td style="height: 30px;"></td>
                                    </tr>  
                                    <tr>
                                        <td class="social_icons" style="text-align: center !important;">
                                            <div class="footerIcons">
                                                <?php  if( $fb_url_status == 1){?>
                                                <a href="<?php echo $facebook_url; ?>"><img src="<?php echo base_url('assets/email/facebook.png'); ?>"></a>
                                                <?php }?>
                                                <?php if( $google_url_status == 1){?>
                                                <a href="<?php echo $google_url; ?>"><img src="<?php echo base_url('assets/email/google_plus.png'); ?>"></a>
                                                <?php }?>
                                                <?php if( $insta_url_status == 1){?>
                                                <a href="<?php echo $insta_url; ?>"><img src="<?php echo base_url('assets/email/instagram.png'); ?>"></a>
                                                <?php }?>
                                            </div>
                                            <p>Copyright &copy; <?php echo APP_NAME.date('Y')?></p>    
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</tbody>
</table>
</body>
</html>