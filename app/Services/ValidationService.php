<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ValidationService
{
    protected $providers = [
        'mobile-legends' => 'moonton',
        'free-fire' => 'garena',
        'pubg-mobile' => 'pubg',
        'genshin-impact' => 'mihoyo',
        'valorant' => 'riot',
        'call-of-duty' => 'activision',
    ];

    public function validatePlayer($game, $uid, $server = null)
    {
        // Cache key for validation result
        $cacheKey = "player_validation:{$game}:{$uid}:{$server}";
        
        // Check cache first
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $provider = $this->providers[$game] ?? null;
        
        if (!$provider) {
            return [
                'valid' => true, // Default to valid if no provider
                'player_name' => null,
                'message' => 'Validation not available for this game',
            ];
        }

        $result = $this->validateByProvider($provider, $uid, $server);
        
        // Cache for 5 minutes
        Cache::put($cacheKey, $result, 300);
        
        return $result;
    }

    protected function validateByProvider($provider, $uid, $server)
    {
        try {
            switch ($provider) {
                case 'moonton':
                    return $this->validateMoonton($uid, $server);
                case 'garena':
                    return $this->validateGarena($uid);
                case 'pubg':
                    return $this->validatePubg($uid);
                case 'mihoyo':
                    return $this->validateMihoyo($uid, $server);
                case 'riot':
                    return $this->validateRiot($uid, $server);
                case 'activision':
                    return $this->validateActivision($uid);
                default:
                    return [
                        'valid' => true,
                        'player_name' => null,
                        'message' => 'Validation passed',
                    ];
            }
        } catch (\Exception $e) {
            Log::error("Player validation error: {$e->getMessage()}");
            
            // Default to valid on error to not block transactions
            return [
                'valid' => true,
                'player_name' => null,
                'message' => 'Validation service temporarily unavailable',
            ];
        }
    }

    protected function validateMoonton($uid, $server)
    {
        // This would connect to actual Moonton API
        // For now, we'll simulate validation
        
        if (!preg_match('/^\d{6,12}$/', $uid)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid User ID format. Please enter 6-12 digits.',
            ];
        }

        if (!preg_match('/^\d{4}$/', $server)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid Server ID format. Please enter 4 digits.',
            ];
        }

        // Simulate API call
        $playerName = "Player" . substr($uid, -4);
        
        return [
            'valid' => true,
            'player_name' => $playerName,
            'message' => 'Player found',
        ];
    }

    protected function validateGarena($uid)
    {
        if (!preg_match('/^\d{8,15}$/', $uid)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid User ID format',
            ];
        }

        return [
            'valid' => true,
            'player_name' => "FF_Player" . substr($uid, -4),
            'message' => 'Player found',
        ];
    }

    protected function validatePubg($uid)
    {
        if (!preg_match('/^\d{10,15}$/', $uid)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid User ID format',
            ];
        }

        return [
            'valid' => true,
            'player_name' => "PUBG_Player" . substr($uid, -4),
            'message' => 'Player found',
        ];
    }

    protected function validateMihoyo($uid, $server)
    {
        if (!preg_match('/^\d{9}$/', $uid)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid UID format. Please enter 9 digits.',
            ];
        }

        $validServers = ['asia', 'europe', 'america', 'tw_hk_mo'];
        if (!in_array($server, $validServers)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid server selected',
            ];
        }

        return [
            'valid' => true,
            'player_name' => "Traveler" . substr($uid, -4),
            'message' => 'Player found',
        ];
    }

    protected function validateRiot($uid, $server)
    {
        // Riot ID format: Name#TAG
        if (!preg_match('/^.+#.+$/', $uid)) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid Riot ID format. Use format: Name#TAG',
            ];
        }

        return [
            'valid' => true,
            'player_name' => explode('#', $uid)[0],
            'message' => 'Player found',
        ];
    }

    protected function validateActivision($uid)
    {
        // Activision ID can be numeric or alphanumeric
        if (strlen($uid) < 5 || strlen($uid) > 20) {
            return [
                'valid' => false,
                'player_name' => null,
                'message' => 'Invalid Activision ID',
            ];
        }

        return [
            'valid' => true,
            'player_name' => "COD_" . $uid,
            'message' => 'Player found',
        ];
    }
}