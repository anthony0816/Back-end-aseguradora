<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Trade;
use App\Models\RiskRule;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_accounts' => Account::count(),
            'active_accounts' => Account::where('status', 'enable')->count(),
            'total_trades' => Trade::count(),
            'open_trades' => Trade::where('status', 'open')->count(),
            'closed_trades' => Trade::where('status', 'closed')->count(),
            'total_rules' => RiskRule::count(),
            'active_rules' => RiskRule::where('is_active', true)->count(),
            'total_incidents' => Incident::count(),
            'executed_incidents' => Incident::where('is_executed', true)->count(),
        ];

        return response()->json($stats, 200);
    }

    public function recentIncidents(Request $request)
    {
        $limit = $request->query('limit', 10);
        
        $incidents = Incident::with(['account', 'riskRule', 'trade'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($incidents, 200);
    }

    public function accountRiskProfile($accountId)
    {
        $account = Account::with(['owner'])->findOrFail($accountId);
        
        $profile = [
            'account' => $account,
            'total_trades' => Trade::where('account_id', $accountId)->count(),
            'open_trades' => Trade::where('account_id', $accountId)->where('status', 'open')->count(),
            'total_incidents' => Incident::where('account_id', $accountId)->count(),
            'recent_incidents' => Incident::where('account_id', $accountId)
                ->with(['riskRule'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'violations_by_rule' => Incident::where('account_id', $accountId)
                ->select('risk_rule_id', DB::raw('count(*) as total'))
                ->groupBy('risk_rule_id')
                ->with('riskRule:id,name')
                ->get(),
        ];

        return response()->json($profile, 200);
    }

    public function userSummary(Request $request)
    {
        $user = $request->user();
        $userAccounts = Account::where('owner_id', $user->id)->pluck('id');
        
        $summary = [
            'user' => $user,
            'accounts_count' => $userAccounts->count(),
            'active_accounts' => Account::where('owner_id', $user->id)->where('status', 'enable')->count(),
            'disabled_accounts' => Account::where('owner_id', $user->id)->where('status', 'disable')->count(),
            'trading_disabled_accounts' => Account::where('owner_id', $user->id)->where('trading_status', 'disable')->count(),
            'total_trades' => Trade::whereIn('account_id', $userAccounts)->count(),
            'open_trades' => Trade::whereIn('account_id', $userAccounts)->where('status', 'open')->count(),
            'total_incidents' => Incident::whereIn('account_id', $userAccounts)->count(),
            'unread_notifications' => \App\Models\Notification::where('user_id', $user->id)->count(),
            'recent_incidents' => Incident::whereIn('account_id', $userAccounts)
                ->with(['account', 'riskRule'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'accounts_status' => Account::where('owner_id', $user->id)
                ->select('id', 'login', 'status', 'trading_status', 'updated_at')
                ->get(),
        ];

        return response()->json($summary, 200);
    }
}
