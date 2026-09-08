<?php

namespace App\Libraries;

/**
 * KYC Validation Service
 * Implements Risk-Based Approach validation according to SOP
 * 
 * Risk Categories:
 * 1. Resiko Rendah (Low Risk) - Auto Approved
 * 2. Resiko Sedang (Medium Risk) - Manual Review
 * 3. Resiko Tinggi (High Risk) - Manual Review + Additional Checks
 */
class KycValidationService
{
    /**
     * Validate KYC data and determine if auto-approval is possible
     * 
     * @param array $userData User data from form submission
     * @return array ['status' => 'approved|pending|rejected', 'risk_level' => 'rendah|sedang|tinggi', 'reason' => 'explanation']
     */
    public function validateAndDeterminStatus($userData)
    {
        // Extract relevant data
        $age = $this->calculateAge($userData['birth_date']);
        $monthlyIncome = $this->parseMonthlyIncome($userData['monthly_income'] ?? 'less_5m');
        $hasInvestmentExperience = !empty($userData['investment_experience']) && $userData['investment_experience'] > 0;
        $hasTradeExperience = $hasInvestmentExperience; // Use investment_experience as proxy for trade experience
        $riskProfile = $userData['type_of_risk'] ?? 'conservative';
        $tradingGoal = $userData['investment_goals'] ?? 'investasi';
        $hasFamily = false; // Not collected in current form
        $hasLegalIssues = false; // Not collected in current form
        $hasMoneyLaunderingConcern = false; // Not collected in current form
        
        // Determine risk level based on profile
        $riskLevel = $this->determineRiskLevel(
            $age,
            $monthlyIncome,
            $hasInvestmentExperience,
            $hasTradeExperience,
            $riskProfile,
            $tradingGoal,
            $hasFamily,
            $hasLegalIssues,
            $hasMoneyLaunderingConcern
        );
        
        // Validate against SOP criteria
        $validation = $this->validateAgainstSop(
            $riskLevel,
            $age,
            $monthlyIncome,
            $hasInvestmentExperience,
            $hasTradeExperience,
            $riskProfile,
            $tradingGoal,
            $hasLegalIssues,
            $hasMoneyLaunderingConcern
        );
        
        return $validation;
    }
    
    /**
     * Determine risk level based on client profile
     * 
     * Risk Levels:
     * - Rendah (Low): Age > 21, Income > Rp 1M, Investment/Trading experience, Low-Medium risk profile
     * - Sedang (Medium): Age > 21, Income > Rp 100jt, Some experience, Medium risk profile
     * - Tinggi (High): Age < 21, Income < Rp 100jt, No experience, High risk profile
     */
    private function determineRiskLevel($age, $monthlyIncome, $hasInvExp, $hasTradeExp, $riskProfile, $tradingGoal, $hasFamily, $hasLegalIssues, $hasMoneyLaunderingConcern)
    {
        // Immediate rejection criteria
        if ($hasLegalIssues || $hasMoneyLaunderingConcern) {
            return 'tinggi'; // High risk - requires manual review
        }
        
        // Low Risk Profile
        // Age > 21, Income > 15M, Investment/Trading experience, Conservative/Moderate risk, Investasi/Hedging goal
        if ($age > 21 && $monthlyIncome >= 15000000 && ($hasInvExp || $hasTradeExp) && in_array($riskProfile, ['conservative', 'moderate']) && in_array($tradingGoal, ['investasi', 'hedging'])) {
            return 'rendah';
        }
        
        // Medium Risk Profile
        // Age > 21, Income 5M-15M, Some experience, Moderate risk, Any goal
        if ($age > 21 && $monthlyIncome >= 5000000 && ($hasInvExp || $hasTradeExp) && $riskProfile === 'moderate') {
            return 'sedang';
        }
        
        // High Risk Profile
        // Age < 21 OR Income < 5M OR No experience OR Aggressive risk OR Spekulasi goal
        if ($age < 21 || $monthlyIncome < 5000000 || (!$hasInvExp && !$hasTradeExp) || $riskProfile === 'aggressive' || $tradingGoal === 'spekulasi') {
            return 'tinggi';
        }
        
        // Default to medium
        return 'sedang';
    }
    
    /**
     * Validate against SOP criteria for each risk level
     */
    private function validateAgainstSop($riskLevel, $age, $monthlyIncome, $hasInvExp, $hasTradeExp, $riskProfile, $tradingGoal, $hasLegalIssues, $hasMoneyLaunderingConcern)
    {
        // Rejection criteria (applies to all risk levels)
        if ($hasLegalIssues) {
            return [
                'status' => 'rejected',
                'risk_level' => $riskLevel,
                'reason' => 'Anda telah dinyatakan bersalah oleh Pengadilan. Tidak dapat melanjutkan proses KYC.'
            ];
        }
        
        if ($hasMoneyLaunderingConcern) {
            return [
                'status' => 'rejected',
                'risk_level' => $riskLevel,
                'reason' => 'Anda terlibat dalam tindakan pencucian uang atau pendanaan terorisme. Tidak dapat melanjutkan proses KYC.'
            ];
        }
        
        // Risk Level: RENDAH (Low Risk) - AUTO APPROVED
        if ($riskLevel === 'rendah') {
            // Criteria for Low Risk:
            // a. Risk profile Klien: Usia > 21 Tahun, Penghasilan > Rp 15 juta
            // b. Risk appetite Klien: Pengalaman Investasi > Ya, Pengalaman Trading > Ya
            // c. Risk objective Klien: Investasi atau Hedging
            
            if ($age > 21 && $monthlyIncome >= 15000000 && $hasInvExp && $hasTradeExp && in_array($riskProfile, ['conservative', 'moderate']) && in_array($tradingGoal, ['investasi', 'hedging'])) {
                return [
                    'status' => 'approved',
                    'risk_level' => 'rendah',
                    'reason' => 'Profil risiko rendah. Verifikasi otomatis berhasil.'
                ];
            }
        }
        
        // Risk Level: SEDANG (Medium Risk) - MANUAL REVIEW
        if ($riskLevel === 'sedang') {
            // Criteria for Medium Risk:
            // a. Risk profile Klien: Usia > 21 Tahun, Penghasilan > Rp 100 juta
            // b. Risk appetite Klien: Pengalaman Investasi > Ya, Pengalaman Trading > Tidak
            // c. Risk objective Klien: Medium Risk Medium Return (50% dari modal)
            
            return [
                'status' => 'pending',
                'risk_level' => 'sedang',
                'reason' => 'Profil risiko sedang. Memerlukan verifikasi manual oleh tim kami.'
            ];
        }
        
        // Risk Level: TINGGI (High Risk) - MANUAL REVIEW + ADDITIONAL CHECKS
        if ($riskLevel === 'tinggi') {
            // Criteria for High Risk:
            // a. Risk profile Klien: Usia < 21 Tahun, Penghasilan < Rp 100 juta
            // b. Risk appetite Klien: Pengalaman Investasi - Tidak, Pengalaman Trading - Tidak
            // c. Risk objective Klien: Low Risk Low Return (20% dari modal)
            
            return [
                'status' => 'pending',
                'risk_level' => 'tinggi',
                'reason' => 'Profil risiko tinggi. Memerlukan verifikasi manual dan pemeriksaan tambahan oleh tim kami.'
            ];
        }
        
        // Default to pending
        return [
            'status' => 'pending',
            'risk_level' => $riskLevel,
            'reason' => 'Data memerlukan verifikasi manual oleh tim kami.'
        ];
    }
    
    /**
     * Calculate age from birth date
     */
    private function calculateAge($birthDate)
    {
        $birthDateTime = new \DateTime($birthDate);
        $today = new \DateTime();
        $age = $today->diff($birthDateTime)->y;
        return $age;
    }
    
    /**
     * Parse monthly income value from form selection
     */
    private function parseMonthlyIncome($incomeRange)
    {
        $incomeMap = [
            'less_5m' => 2500000,      // < 5M = 2.5M average
            '5m_15m' => 10000000,      // 5-15M = 10M average
            '15m_50m' => 32500000,     // 15-50M = 32.5M average
            'more_50m' => 75000000,    // > 50M = 75M average
        ];
        return $incomeMap[$incomeRange] ?? 0;
    }
}
