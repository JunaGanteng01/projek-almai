<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDataModel extends Model
{
    protected $table = 'user_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Allowed fields based on the wide table schema provided by the user
    protected $allowedFields = [
        'user_id',
        'full_name',
        'phone',
        'user_pro_statement',
        'professional_trading_experience',
        'address',
        'identity_type',
        'identity_number',
        'npwp',
        'profession',
        'company',
        'position',
        'business_line',
        'tenure',
        'company_address',
        'company_postcode',
        'annually_income',
        'investment_experience',
        'investment_type',
        'trading_experience',
        'trading_company',
        'length_of_trading',
        'registration_purpose',
        'trader_family',
        'bankrupt',
        'involved_in_crime',
        'deposit',
        'other_assets',
        'asset_value',
        'photo_identity_card',
        'photo_selfie',
        'account_name',
        'account_number',
        'photo_savings_account',
        'bank_name',
        'type_of_risk',
        'investment_goals',
        'additional_document',
        'statement_of_truth',
        'agreed',
        'agreed_at',
        'risk_reward_expectation',
        'loss_tolerance',
        'prepared_modal',
        'other_investment_experience',
        'birth_place_and_date',
        'address_as_per_ID',
        'community_name',
        'founding_details',
        'community_type',
        'number_of_members',
        'trading_strategy',
        'strategy_name',
        'has_cwpa_certificate',
        'tlup_number',
        'bank_branch',
        'full_name',
        'birth_place',
        'birth_date',
        'gender',
        'province',
        'city',
        'district',
        'village',
        'postal_code'
    ];

    protected $useTimestamps = true;
}
