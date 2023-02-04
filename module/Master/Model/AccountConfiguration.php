<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class AccountConfiguration extends BaseModel
{
    protected $fillable = [
        'wa_invoice_template', 'wa_invoice_template_language', 
        'wa_business_account_id', 'wa_phone_number_id', 'wa_access_token',
        'mail_host', 'mail_port', 'mail_user', 'mail_pass'
    ];
    protected $table = 'account_configuration';
}
