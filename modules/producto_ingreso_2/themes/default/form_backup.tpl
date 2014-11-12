<!--
<table class="otra_tabla" width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
    <tr class="letra12">
        {if $mode eq 'input'}
        <td align="left">
            <input class="button" type="submit" name="save_new" value="{$SAVE}">&nbsp;&nbsp;
            <input class="button" type="submit" name="cancel" value="{$CANCEL}">
        </td>
        {elseif $mode eq 'view'}
        <td align="left">
            <input class="button" type="submit" name="cancel" value="{$CANCEL}">
        </td>
        {elseif $mode eq 'edit'}
        <td align="left">
            <input class="button" type="submit" name="save_edit" value="{$EDIT}">&nbsp;&nbsp;
            <input class="button" type="submit" name="cancel" value="{$CANCEL}">
        </td>
        {/if}
        <td align="right" nowrap><span class="letra12"><span  class="required">*</span> {$REQUIRED_FIELD}</span></td>
    </tr>
</table>
<table class="tabForm" style="font-size: 16px;" width="100%" >
    <tr class="letra12">
        <td align="left" width="20%"><b>{$codigo.LABEL}: <span  class="required">*</span></b></td>
        <td align="left">{$codigo.INPUT}</td>
    </tr>
    <tr class="letra12">
        <td align="left" width="20%"><b>{$nombre.LABEL}: <span  class="required">*</span></b></td>
        <td align="left">{$nombre.INPUT}</td>
    </tr>
    <tr class="letra12">
        <td align="left" width="20%"><b>{$descripcion.LABEL}: <span  class="required">*</span></b></td>
        <td align="left">{$descripcion.INPUT}</td>
    </tr>
</table>
<input class="button" type="hidden" name="id" value="{$ID}" />
-->
<div class="form-style-10">
<h1>Sign Up Now!<span>Sign up and tell us what you think of the site!</span></h1>
<form>
    <div class="section"><span>1</span>First Name &amp; Address</div>
    <div class="inner-wrap">
        <label>Your Full Name <input type="text" name="field1" /></label>
        <label>Address <textarea name="field2"></textarea></label>
    </div>

    <div class="section"><span>2</span>Email &amp; Phone</div>
    <div class="inner-wrap">
        <label>Email Address <input type="email" name="field3" /></label>
        <label>Phone Number <input type="text" name="field4" /></label>
    </div>

    <div class="section"><span>3</span>Passwords</div>
        <div class="inner-wrap">
        <label>Password <input type="password" name="field5" /></label>
        <label>Confirm Password <input type="password" name="field6" /></label>
    </div>
    <div class="button-section">
     <input type="submit" name="Sign Up" />
     <span class="privacy-policy">
     <input type="checkbox" name="field7">You agree to our Terms and Policy. 
     </span>
    </div>
</form>
</div>

