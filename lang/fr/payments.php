<?php

return [
    /* Page meta */
    'page_title'   => 'Paiements',
    'page_desc'    => 'Tranches, montants et statuts.',
    'stat_label'   => 'Opérations',
    'create_title' => 'Enregistrer un paiement',
    'create_desc'  => 'Le montant suggéré provient des tarifs de scolarité (niveau et section).',
    'edit_title'   => 'Modifier le paiement',
    'edit_desc'    => 'Statut, mode et montant.',
    'show_title'   => 'Détail du paiement',

    /* Panel */
    'registry'          => 'Registre des paiements',
    'registry_subtitle' => 'Recherchez un élève, un type de paiement ou un mode d\'encaissement.',
    'search_label'      => 'Recherche locale',
    'search_placeholder'=> 'Élève, type, mode ou statut…',
    'new_payment'       => 'Nouveau paiement',
    'pending_alert'     => 'paiement(s) en attente de validation.',

    /* Table columns */
    'col_student'   => 'Élève',
    'col_type'      => 'Type',
    'col_amount'    => 'Montant',
    'col_method'    => 'Mode',
    'col_reference' => 'Référence',
    'col_status'    => 'Statut',

    /* Delete */
    'delete_confirm' => 'Supprimer ce paiement ?',

    /* Empty states */
    'empty_title'   => 'Aucun paiement',
    'empty_desc'    => 'Aucun paiement n\'a encore été enregistré.',
    'no_match_desc' => 'Aucun paiement ne correspond à cette recherche.',

    /* Show */
    'info_classroom'   => 'Classe',
    'info_parent'      => 'Parent',
    'info_method'      => 'Mode',
    'info_reference'   => 'Référence',
    'info_receipt'     => 'Reçu',
    'info_channel'     => 'Canal',
    'info_received_by' => 'Enregistré par',
    'info_validated_by'=> 'Validé par',
    'receipt_pdf'      => 'Reçu PDF',
    'validate'         => 'Valider le paiement',
    'tuition_balance'  => 'Solde scolarité',
    'remaining_label'  => 'Reste à payer :',
    'col_installment'  => 'Tranche',
    'col_due'          => 'Dû',
    'col_paid'         => 'Payé',
    'col_remaining'    => 'Reste',

    /* Declare page */
    'declare_title'    => 'Déclarer un paiement',
    'declare_desc'     => 'Indiquez votre règlement ; la scolarité le validera après vérification.',
    'school_accounts'  => 'Coordonnées de paiement de l\'école',
    'online_payment'   => 'Paiement en ligne',
    'online_badge'     => 'Recommandé',
    'online_desc'      => 'Orange Money ou MTN MoMo avec confirmation automatique par webhook opérateur.',
    'pay_online_btn'   => 'Payer en ligne',
    'manual_declare'   => 'Déclaration manuelle',
    'manual_desc'      => 'Après virement ou paiement hors ligne, indiquez la référence pour validation par la scolarité.',
    'declare_child'    => 'Enfant',
    'declare_send'     => 'Envoyer la déclaration',

    /* Pending page */
    'pending_title'    => 'Paiement en attente',
    'operator_label'   => 'Opérateur',
    'status_operator'  => 'Statut',
    'my_payments'      => 'Mes paiements',
    'download_receipt' => 'Télécharger le reçu PDF',
    'pending_note'     => 'Dès confirmation par :operator, le paiement passera automatiquement à « Payé » et vous recevrez une notification (et un SMS si activé).',

    /* Online payment page */
    'pay_title'         => 'Payer avec Orange Money ou MTN MoMo',
    'pay_desc'          => 'Le paiement est confirmé automatiquement par l\'opérateur (webhook). Un reçu PDF sera disponible après validation.',
    'pay_btn'           => 'Lancer le paiement',
    'form_operator'     => 'Opérateur',
    'form_payer_phone'  => 'Numéro mobile du payeur',
    'form_phone_hint'   => 'Numéro Orange ou MTN utilisé pour valider la transaction.',
    'pay_footer_before' => 'Vous pouvez aussi ',
    'pay_footer_link'   => 'déclarer un paiement manuel',
    'pay_footer_after'  => ' avec référence.',

    /* Form labels */
    'form_student'     => 'Élève',
    'form_type'        => 'Type de paiement',
    'form_amount'      => 'Montant',
    'form_reference'   => 'Référence du paiement',
    'form_method'      => 'Mode de paiement',
    'form_account_ref' => 'Numéro de compte ou transaction',
    'form_status'      => 'Statut',
    'form_notes'       => 'Notes',
    'form_amount_fcfa' => 'Montant (FCFA)',
    'form_ref_tx'      => 'Référence transaction',
    'form_account_used'=> 'Compte / numéro utilisé',
    'form_select'      => 'Sélectionner',
];
