<?php

return [
    /* Page meta */
    'page_title'   => 'Payments',
    'page_desc'    => 'Installments, amounts and statuses.',
    'stat_label'   => 'Transactions',
    'create_title' => 'Record a payment',
    'create_desc'  => 'The suggested amount comes from the tuition fee schedule (level and section).',
    'edit_title'   => 'Edit payment',
    'edit_desc'    => 'Status, method and amount.',
    'show_title'   => 'Payment details',

    /* Panel */
    'registry'          => 'Payment register',
    'registry_subtitle' => 'Search by student, payment type or collection method.',
    'search_label'      => 'Local search',
    'search_placeholder'=> 'Student, type, method or status…',
    'new_payment'       => 'New payment',
    'pending_alert'     => 'payment(s) pending validation.',

    /* Table columns */
    'col_student'   => 'Student',
    'col_type'      => 'Type',
    'col_amount'    => 'Amount',
    'col_method'    => 'Method',
    'col_reference' => 'Reference',
    'col_status'    => 'Status',

    /* Delete */
    'delete_confirm' => 'Delete this payment?',

    /* Empty states */
    'empty_title'   => 'No payments',
    'empty_desc'    => 'No payment has been recorded yet.',
    'no_match_desc' => 'No payment matches this search.',

    /* Show */
    'info_classroom'   => 'Class',
    'info_parent'      => 'Parent',
    'info_method'      => 'Method',
    'info_reference'   => 'Reference',
    'info_receipt'     => 'Receipt',
    'info_channel'     => 'Channel',
    'info_received_by' => 'Recorded by',
    'info_validated_by'=> 'Validated by',
    'receipt_pdf'      => 'PDF Receipt',
    'validate'         => 'Validate payment',
    'tuition_balance'  => 'Tuition balance',
    'remaining_label'  => 'Remaining balance:',
    'col_installment'  => 'Installment',
    'col_due'          => 'Due',
    'col_paid'         => 'Paid',
    'col_remaining'    => 'Remaining',

    /* Declare page */
    'declare_title'    => 'Declare a payment',
    'declare_desc'     => 'Submit your payment details; the registrar will validate it after verification.',
    'school_accounts'  => 'School payment details',
    'online_payment'   => 'Online payment',
    'online_badge'     => 'Recommended',
    'online_desc'      => 'Orange Money or MTN MoMo with automatic confirmation via operator webhook.',
    'pay_online_btn'   => 'Pay online',
    'manual_declare'   => 'Manual declaration',
    'manual_desc'      => 'After a bank transfer or offline payment, enter the reference for validation by the registrar.',
    'declare_child'    => 'Child',
    'declare_send'     => 'Send declaration',

    /* Pending page */
    'pending_title'    => 'Payment pending',
    'operator_label'   => 'Operator',
    'status_operator'  => 'Status',
    'my_payments'      => 'My payments',
    'download_receipt' => 'Download PDF receipt',
    'pending_note'     => 'Upon confirmation by :operator, the payment will automatically move to "Paid" and you will receive a notification (and an SMS if enabled).',

    /* Online payment page */
    'pay_title'         => 'Pay with Orange Money or MTN MoMo',
    'pay_desc'          => 'Payment is automatically confirmed by the operator (webhook). A PDF receipt will be available after validation.',
    'pay_btn'           => 'Start payment',
    'form_operator'     => 'Operator',
    'form_payer_phone'  => "Payer's mobile number",
    'form_phone_hint'   => 'Orange or MTN number used to validate the transaction.',
    'pay_footer_before' => 'You can also ',
    'pay_footer_link'   => 'submit a manual declaration',
    'pay_footer_after'  => ' with a reference.',

    /* Form labels */
    'form_student'     => 'Student',
    'form_type'        => 'Payment type',
    'form_amount'      => 'Amount',
    'form_reference'   => 'Payment reference',
    'form_method'      => 'Payment method',
    'form_account_ref' => 'Account or transaction number',
    'form_status'      => 'Status',
    'form_notes'       => 'Notes',
    'form_amount_fcfa' => 'Amount (FCFA)',
    'form_ref_tx'      => 'Transaction reference',
    'form_account_used'=> 'Account / number used',
    'form_select'      => 'Select',
];
