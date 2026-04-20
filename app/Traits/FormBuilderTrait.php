<?php

namespace App\Traits;

use App\ErrorLogs;


trait FormBuilderTrait
{
    public function userForm($action, $group, $value, $groupId,$QR_Image,$secret)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Basic Information',
                    'size' => '6',
                    'form_fields' => array(
                        array(
                            'name' => 'first_name',
                            'type' => 'text',
                            'size' => '4',
                            'placeholder' => 'First Name',
                            'value' => isset($value->first_name) ? $value->first_name : ''
                        ),
                        array(
                            'name' => 'middle_name',
                            'type' => 'text',
                            'size' => '4',
                            'placeholder' => 'Middle Name',
                            'value' => isset($value->middle_name) ? $value->middle_name : ''
                        ),
                        array(
                            'name' => 'last_name',
                            'type' => 'text',
                            'size' => '4',
                            'placeholder' => 'Last Name',
                            'value' => isset($value->last_name) ? $value->last_name : ''
                        ),
                        array(
                            'name' => 'dob',
                            'type' => 'date',
                            'size' => '4',
                            'placeholder' => 'Date of Birth',
                            'value' => isset($value->dob) ? $value->dob : ''
                        ),
                        array(
                            'name' => 'group_id',
                            'type' => 'dropdown',
                            'size' => '6',
                            'placeholder' => 'Group',
                            'options' => $group,
                            'value' => isset($groupId) ? $groupId : '',
                            'id' => 'checkuserattr'
                        ),

                    )
                ),
                array(
                    'form_name' => 'Contact Information',
                    'size' => '6',
                    'form_fields' => array(
                        array(
                            'name' => 'email',
                            'type' => 'text',
                            'size' => '6',
                            'placeholder' => 'Email Address',
                            'value' => isset($value->email) ? $value->email : ''
                        ),
                        array(
                            'name' => 'mobile',
                            'type' => 'text',
                            'size' => '6',
                            'placeholder' => 'Mobile Number',
                            'value' => isset($value->mobile) ? $value->mobile : ''
                        ),
                        array(
                            'name' => 'password',
                            'type' => 'password',
                            'size' => '6',
                            'placeholder' => 'Password',
                            'value' => ''
                        ),
                        array(
                            'name' => 'status',
                            'type' => 'checkbox',
                            'size' => '6',
                            'placeholder' => 'Status',
                            'value' => isset($value->status) ? $value->status : ''
                        ),
                    )
                ),
                array(
                    'form_name' => 'Google 2FA QR Code',
                    'size' => '6',
                    'form_fields' => array(
                        array(
                            'name' => 'qr',
                            'type' => 'qr',
                            'size' => '12',
                            'value' => $QR_Image,
                            'secret' => $secret,
                        ),
                    )
                ),
            ),
        );
        return $form;
    }
    public function importForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'File Importer',
                    'size' => '12',
                    'form_fields' => array(
                        array(
                            'name' => 'file',
                            'type' => 'file',
                            'size' => '4',
                            'placeholder' => 'Upload File',
                            'value' => ''
                        ),
                    )
                )
            ),
        );
        return $form;
    }
    public function groupForm($action, $value)
    {
        $test = [];

        for ($i = 0; $i < 5; $i++) {
            array_push(
                $test,
                array(
                    'name' => 'permission[]',
                    'size' => '12',
                    'type' => 'checkbox',
                    'placeholder' => 'Permission',
                    'value' => isset($value->enabled) ? $value->enabled : ''
                )
            );
        }

        $form_fill = array_merge(
            $test,
            array(
                array(
                    'name' => 'name',
                    'size' => '12',
                    'type' => 'text',
                    'placeholder' => 'Name',
                    'value' => isset($value->name) ? $value->name : ''
                ),
                array(
                    'name' => 'description',
                    'size' => '12',
                    'type' => 'textarea',
                    'placeholder' => 'Description',
                    'value' => isset($value->description) ? $value->description : ''
                ),
                array(
                    'name' => 'enabled',
                    'size' => '12',
                    'type' => 'checkbox',
                    'placeholder' => 'Status',
                    'value' => isset($value->enabled) ? $value->enabled : ''
                ),
            )
        );

        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Group',
                    'size' => '12',

                    'form_fields' => $form_fill
                ),


            ),
        );
        return $form;
    }
    public function moduleForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Module',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'description',
                            'size' => '12',
                            'type' => 'textarea',
                            'placeholder' => 'Description',
                            'value' => isset($value->description) ? $value->description : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }
    public function moduleActionForm($action, $module, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Module',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'module_id',
                            'type' => 'dropdown',
                            'size' => '6',
                            'placeholder' => 'Module',
                            'options' => $module,
                            'value' => isset($value->module_id) ? $value->module_id : '',
                            'id' => 'checkuserattr'
                        ),
                        array(
                            'name' => 'action',
                            'size' => '12',
                            'type' => 'multi',
                            'placeholder' => 'Action',
                            'value' => isset($value->action) ? $value->action : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }
    public function bankForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Bank',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'fields',
                            'size' => '12',
                            'type' => 'multi',
                            'placeholder' => 'fields',
                            'value' => isset($value->fields) ? $value->fields : ''
                        ),
                        array(
                            'name' => 'status',
                            'size' => '12',
                            'type' => 'checkbox',
                            'placeholder' => 'Status',
                            'value' => isset($value->status) ? $value->status : ''
                        ),
                        array(
                            'name' => 'logo',
                            'size' => '12',
                            'type' => 'image',
                            'placeholder' => 'fields',
                            'value' => isset($value->fields) ? $value->fields : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }
    public function questionForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Question',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'questions',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Question',
                            'value' => isset($value->questions) ? $value->questions : ''
                        ),
                        array(
                            'name' => 'description',
                            'size' => '12',
                            'type' => 'textarea',
                            'placeholder' => 'Description',
                            'value' => isset($value->description) ? $value->description : ''
                        ),
                        array(
                            'name' => 'status',
                            'size' => '12',
                            'type' => 'checkbox',
                            'placeholder' => 'Status',
                            'value' => isset($value->status) ? $value->status : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }

    public function termsForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Terms & Conditions',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'terms',
                            'size' => '12',
                            'type' => 'textarea',
                            'placeholder' => 'Terms',
                            'value' => isset($value->terms) ? $value->terms : ''
                        ),
                        array(
                            'name' => 'status',
                            'size' => '12',
                            'type' => 'checkbox',
                            'placeholder' => 'Status',
                            'value' => isset($value->status) ? $value->status : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }

    public function reasonForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Reason',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'file',
                            'size' => '12',
                            'type' => 'file',
                            'placeholder' => 'Terms',
                            'value' => isset($value->image) ? $value->image : ''
                        ),
                        array(
                            'name' => 'status',
                            'size' => '12',
                            'type' => 'checkbox',
                            'placeholder' => 'Status',
                            'value' => isset($value->status) ? $value->status : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }

    public function reportForm($action, $value, $report_type)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Generate Report',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'report_type',
                            'type' => 'dropdown',
                            'size' => '4',
                            'placeholder' => 'Report Type',
                            'options' => $report_type,
                            'value' => '',
                            'id' => 'checkuserattr'
                        ),
                        array(
                            'name' => 'date_from',
                            'size' => '4',
                            'type' => 'date',
                            'placeholder' => 'Date From',
                            'value' => isset($value->date_from) ? $value->date_from : ''
                        ),
                        array(
                            'name' => 'date_to',
                            'size' => '4',
                            'type' => 'date',
                            'placeholder' => 'Date To',
                            'value' => isset($value->date_to) ? $value->date_to : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }
    public function cohortReportForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Generate Cohort Report',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'start_from',
                            'size' => '6',
                            'type' => 'text',
                            'placeholder' => 'Cohort Number Start from',
                            'value' => isset($value->start_from) ? $value->start_from : ''
                        ),
                        array(
                            'name' => 'number_of_loans',
                            'size' => '6',
                            'type' => 'text',
                            'placeholder' => 'Number of loans in each Cohort',
                            'value' => isset($value->number_of_loans) ? $value->number_of_loans : ''
                        ),
                        array(
                            'name' => 'date_from',
                            'size' => '4',
                            'type' => 'date',
                            'placeholder' => 'Date From',
                            'value' => isset($value->date_from) ? $value->date_from : ''
                        ),
                        array(
                            'name' => 'date_to',
                            'size' => '4',
                            'type' => 'date',
                            'placeholder' => 'Date To',
                            'value' => isset($value->date_to) ? $value->date_to : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }

    public function documentType($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Document type',
                    'size' => '12',
                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        )
                    )
                ),
            ),
        );
        return $form;
    }
    public function emailTemplate($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Email Template',
                    'size' => '12',
                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '6',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'subject',
                            'size' => '6',
                            'type' => 'text',
                            'placeholder' => 'Subject',
                            'value' => isset($value->subject) ? $value->subject : ''
                        ),
                        array(
                            'name' => 'body',
                            'size' => '12',
                            'type' => 'textarea',
                            'placeholder' => 'Content',
                            'value' => isset($value->body) ? $value->body : ''
                        ),
                    )
                ),
            ),
        );
        return $form;
    }
    public function SMSTemplate($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'SMS Template',
                    'size' => '12',
                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '6',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'subject',
                            'size' => '6',
                            'type' => 'text',
                            'placeholder' => 'Subject',
                            'value' => isset($value->subject) ? $value->subject : ''
                        ),
                        array(
                            'name' => 'body',
                            'size' => '12',
                            'type' => 'textbox',
                            'placeholder' => 'Content',
                            'value' => isset($value->body) ? $value->body : ''
                        ),
                    )
                ),
            ),
        );
        return $form;
    }

    public function scoreForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Create Activity Score',
                    'size' => '12',

                    'form_fields' => array(
                        array(
                            'name' => 'name',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'value' => isset($value->name) ? $value->name : ''
                        ),
                        array(
                            'name' => 'score',
                            'size' => '12',
                            'type' => 'text',
                            'placeholder' => 'Score',
                            'value' => isset($value->score) ? $value->score : ''
                        ),
                    )
                ),


            ),
        );
        return $form;
    }

    public function walletForm($action, $value)
    {
        $form = array(
            'action' => $action,
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => 'Wallet',
                    'size' => '6',
                    'form_fields' => array(
                        array(
                            'name' => 'bsb',
                            'type' => 'text',
                            'size' => '6',
                            'placeholder' => 'bsb',
                            'value' => isset($value->bsb) ? $value->bsb : ''
                        ),
                        array(
                            'name' => 'accountNo',
                            'type' => 'text',
                            'size' => '6',
                            'placeholder' => 'Account Number',
                            'value' => isset($value->accountNo) ? $value->accountNo : ''
                        ),
                        array(
                            'name' => 'amount',
                            'type' => 'text',
                            'size' => '12',
                            'placeholder' => 'Amount',
                            'value' => isset($value->amount) ? $value->amount : ''
                        ),


                    )
                ),

            ),
        );
        return $form;
    }
}
