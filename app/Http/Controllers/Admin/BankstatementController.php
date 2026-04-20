<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankStatement;
use App\Models\LoanApplication;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Carbon\Carbon;

class BankstatementController extends Controller
{
    use GeneralTrait;
    private $access_token = null;
    private $basiq_user_id = null;


    private $url = 'loan.index';


    public function index($basiq_user_id)
    {
        try {
            $this->basiq_user_id = $basiq_user_id;
            $data['breadcrumb'] = $this->breadcrumb('Bank Statement', 'Detail Statement', $this->url, null);
            $user = User::where('basiq_user_id', $basiq_user_id)->first();
            //dd($user->latestbankStatements);
            $bankStatement = $user->latestbankStatements;

            if ($bankStatement == null) {
                if ($this->authenticate()) {
                    $accounts = $this->accountList();
                    $transactions = $this->transactions();
                    //Save Bank Details
                    $bankStatement = new BankStatement();
                    $bankStatement->accounts = json_encode($accounts);
                    $bankStatement->statement = json_encode($transactions);
                    $bankStatement->user_id = $user->id;
                    $bankStatement->save();
                }
            }

            $data['accounts'] = json_decode($bankStatement->accounts);
            $data['statements'] = json_decode($bankStatement->statement);
            $data['basiq_user_id'] = $basiq_user_id;


            return view('admin.bankDetail.detail', $data);
        } catch (\Exception $e) {
            // Handle the exception (log it, display an error message, etc.)
            return redirect()->route('error.404');
        }
    }

    public function updateBankStatement($basiq_user_id)
    {
        $this->basiq_user_id = $basiq_user_id;

        $user = User::where('basiq_user_id', $basiq_user_id)->first();

        if ($this->authenticate()) {
            $this->refresh();
            $accounts = $this->accountList();
            $transactions = $this->transactions();
            //Save Bank Details
            $bankStatement = new BankStatement();
            $bankStatement->accounts = json_encode($accounts);
            $bankStatement->statement = json_encode($transactions);
            $bankStatement->user_id = $user->id;
            $bankStatement->save();

            return redirect()->back()->with('success', 'Bank Statement has been successfully updated.');
        }

        return redirect()->back()->with('error', 'Failed to update bank account. Please check your information and try again.');



    }

    public function viewAffordabilityStatement($basiq_user_id)
    {
        try {
            $this->basiq_user_id = $basiq_user_id;
            $data['breadcrumb'] = $this->breadcrumb('Affordability Report', 'Summary', $this->url, null);
            $user = User::where('basiq_user_id', $basiq_user_id)->first();

            if ($user === null) {
                // User not found, redirect to 404 page
                return redirect()->route('error.404');
            }

            $bankStatement = $user->latestAffordabilitybankStatements;

            if ($bankStatement == null) {
                if ($this->authenticate()) {
                    $bankStatement = $this->createAffordability($basiq_user_id);
                }
            }

            $data['statements'] = json_decode($bankStatement->statement);
            $data['basiq_user_id'] = $basiq_user_id;

            return view('admin.bankDetail.affordability', $data);
        } catch (\Exception $e) {
            // Handle the exception (log it, display an error message, etc.)
            return redirect()->route('error.404');
        }
    }


    public function updateAffordability($basiq_user_id)
    {
        if ($this->authenticate()) {
            $bankStatement = $this->createAffordability($basiq_user_id);
            return redirect()->back()->with('success', 'Bank Statement has been successfully updated.');
        }
        return redirect()->back()->with('error', 'Failed to update bank account. Please check your information and try again.');
    }
    public function createAffordability($basiq_user_id)
    {
        $this->basiq_user_id = $basiq_user_id;

        $user = User::where('basiq_user_id', $basiq_user_id)->first();
        $allstatement = array();

        if ($this->authenticate()) {
            $statement = $this->affordability();
            $allstatement['main'] = $statement;

            if (isset($statement['links']['income'])) {
                $allstatement['income'] = $this->getincomeExpenses($statement['links']['income']);

            }
            if (isset($statement['links']['expenses'])) {
                $allstatement['expenses'] = $this->getincomeExpenses($statement['links']['expenses']);

            }

            //$transactions = $this->transactions();
            //Save Bank Details
            $bankStatement = new BankStatement();
            $bankStatement->accounts = null;
            $bankStatement->type = 'affordability';
            $bankStatement->statement = json_encode($allstatement);
            $bankStatement->user_id = $user->id;
            $bankStatement->save();

            return $bankStatement;
        }

        return;

    }



    public function authenticate()
    {
        $client = new Client();

        try {
            $response = $client->post(env('BASIQ_API_URL') . 'token', [
                'headers' => [
                    'Authorization' => 'Basic ' . env('BASIQ_API_KEY'),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'basiq-version' => '2.0',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $this->access_token = $data['access_token'];
            return true;

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function accountList()
    {

        $client = new Client();

        try {
            $response = $client->get(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . '/accounts/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['data'];

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function transactions()
    {
        $client = new Client();

        try {
            $response = $client->get(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . '/transactions/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData['data'];

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function refresh()
    {
        $client = new Client();

        try {
            $response = $client->post(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . '/connections/refresh', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['data'];

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function accountStatment($basiq_user_id, $accountID)
    {
        $this->basiq_user_id = $basiq_user_id;

        $user = User::where('basiq_user_id', $basiq_user_id)->first();

        if ($this->authenticate()) {

            $accounts = $this->accountDetail($accountID);
            $transactions = $this->Individualtransactions($accountID);
        }

        $data['account'] = $accounts;
        $data['statements'] = $transactions;
        $data['basiq_user_id'] = $basiq_user_id;
        //dd($data);
        return view('admin.bankDetail.accountdetail', $data);
    }

    public function accountDetail($accountID)
    {
        $client = new Client();

        try {
            $response = $client->get(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . '/accounts/' . $accountID, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function Individualtransactions($accountID)
    {
        $client = new Client();

        try {
            $response = $client->get(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . "/transactions?filter=account.id.eq('" . $accountID . "')", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['data'];

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function affordability()
    {
        $client = new Client();

        try {
            $response = $client->post(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . "/affordability", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function getincomeExpenses($url)
    {
        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;

        } catch (\Exception $e) {

            dd($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function viewReport($basiq_user_id)
    {
        set_time_limit(120);
        try {
            $this->basiq_user_id = $basiq_user_id;

            $data['breadcrumb'] = $this->breadcrumb('Consumer Affordability Report', 'Report', $this->url, null);
            $user = User::where('basiq_user_id', $basiq_user_id)->first();
            $bankStatement = $user->latestConsumerAffordabilitybankStatements;

            if ($bankStatement == null) {//
                if ($this->authenticate()) {
                    $this->refresh();
                    $accounts = $this->accountList();

                    $account_list = array();

                    foreach ($accounts as $account) {
                        $account_list[] = $account['id'];
                    }

                    $report_id = $this->createConsumerAffordability($user, $account_list);
                    sleep(60);
                    $report = $this->viewReportIDwise($report_id);

                    $bankStatement = new BankStatement();
                    $bankStatement->accounts = json_encode($report);
                    $bankStatement->report_id = $report_id;
                    $bankStatement->type = 'report';
                    $bankStatement->statement = json_encode($report);
                    $bankStatement->user_id = $user->id;
                    $bankStatement->save();

                }
            }

            $data['accounts'] = json_decode($bankStatement->accounts);
            $data['statements'] = json_decode($bankStatement->statement);
            $data['basiq_user_id'] = $basiq_user_id;

            //Group Metrics
            $groupedMetrics = [];
            foreach ($data['statements']->data->metrics as $item) {
                $section = reset($item->sections); // Assuming each item has one section
                $groupedMetrics[$section][] = $item;
            }

            //Group Metrics
            $groupedGroups = [];
            foreach ($data['statements']->data->groups as $item) {
                $section = reset($item->sections); // Assuming each item has one section
                $groupedGroups[$section][] = $item;
            }


            $monthlyBalance = 0;
            foreach ($groupedMetrics['Summary'] as $s) {
                $monthlyBalance = $s->result->value - $monthlyBalance;
            }

            $groupedMetrics['Summary'][] = [
                'title' => 'SURPLUS',
                'id' => 'ME042',
                'description' => 'Balance after subtracting income and expenses',
                'result' => [
                    'value' => $monthlyBalance,
                    'format' => 'money',
                ],
            ];


            $summary = $groupedMetrics["Summary"];
            unset($groupedMetrics["Summary"]);
            $groupedMetrics = ["Summary" => $summary] + $groupedMetrics;

            $data['metrics'] = json_decode(json_encode($groupedMetrics));
            $data['group'] = json_decode(json_encode($groupedGroups));
            $data['user'] = $user;


            //dd($data);
            return view('admin.bankDetail.report', $data);
        } catch (\Exception $e) {

            return redirect()->route('error.404');
        }
    }

    public function updateConsumerStatement($basiq_user_id)
    {
        set_time_limit(120);

        $this->basiq_user_id = $basiq_user_id;
        $user = User::where('basiq_user_id', $basiq_user_id)->first();
        if ($this->authenticate()) {
            $this->refresh();
            $accounts = $this->accountList();

            $account_list = array();

            foreach ($accounts as $account) {
                $account_list[] = $account['id'];
            }

            $report_id = $this->createConsumerAffordability($user, $account_list);
            sleep(60);
            $report = $this->viewReportIDwise($report_id);

            $bankStatement = new BankStatement();
            $bankStatement->accounts = json_encode($report);
            $bankStatement->report_id = $report_id;
            $bankStatement->type = 'report';
            $bankStatement->statement = json_encode($report);
            $bankStatement->user_id = $user->id;
            $bankStatement->save();
            return redirect()->back()->with('success', 'Consumer Statement has been successfully updated.');
        }


        return redirect()->back()->with('error', 'Failed to update bank account. Please check your information and try again.');
    }
    public function createConsumerAffordability($user, $account)
    {
        try {

            $title = $user->first_name . ' ' . $user->last_name . ' Report 2022-03-26';
            $fromDate = Carbon::now()->subYear()->format('Y-m-d');
            $todate = Carbon::yesterday()->endOfDay()->toDateString();


            $client = new Client();
            if ($this->authenticate()) {

                $response = $client->request('POST', 'https://au-api.basiq.io/reports', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->access_token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'reportType' => 'CON_AFFOR_01',
                        'title' => $title,
                        'filters' => [
                            [
                                'name' => 'fromDate',
                                'value' => $fromDate,
                            ],
                            [
                                'name' => 'toDate',
                                'value' => $todate,
                            ],
                            [
                                'name' => 'accounts',
                                'value' => $account,
                            ],

                            [
                                'name' => 'users',
                                'value' => [$this->basiq_user_id],
                            ],
                        ],
                    ]),
                ]);

                $responseData = json_decode($response->getBody()->getContents(), true);

                return $responseData['id'];
            }

        } catch (RequestException $e) {
            $response = $e->getResponse();

            if ($response) {
                $body = $response->getBody()->getContents();
                $errorDetails = json_decode($body, true);

                if ($errorDetails && isset($errorDetails['data'])) {
                    foreach ($errorDetails['data'] as $error) {
                        dd($error);
                    }
                }
            } else {
                echo 'No response body available.';
            }
            dd();
        }

    }

    public function viewReportIDwise($report_id)
    {

        try {

            $client = new Client();

            $response = $client->request('GET', 'https://au-api.basiq.io/reports/' . $report_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;
        } catch (RequestException $e) {
            $response = $e->getResponse();

            if ($response) {
                $body = $response->getBody()->getContents();
                $errorDetails = json_decode($body, true);

                if ($errorDetails && isset($errorDetails['data'])) {
                    foreach ($errorDetails['data'] as $error) {
                        dd($error);
                    }
                }
            } else {
                echo 'No response body available.';
            }
        }
    }

    public function generateConsumerStatement(Request $request)
    {
        try {
            set_time_limit(120);

            $this->basiq_user_id = $request->input('id');

            $user = User::where('basiq_user_id', $this->basiq_user_id)->firstOrFail();

            if ($this->authenticate()) {
                $this->refresh();
                $accounts = $this->accountList();

                $account_list = [];

                foreach ($accounts as $account) {
                    $account_list[] = $account['id'];
                }

                $report_id = $this->createConsumerAffordability($user, $account_list);
                sleep(60);
                $report = $this->viewReportIDwise($report_id);

                $bankStatement = new BankStatement();
                $bankStatement->accounts = json_encode($report);
                $bankStatement->report_id = $report_id;
                $bankStatement->type = 'report';
                $bankStatement->statement = json_encode($report);
                $bankStatement->user_id = $user->id;
                $bankStatement->save();

                return response()->json(['success' => 'Consumer Affordability Report generated successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to authenticate. Please check your credentials and try again.'], 500);
            }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'An error occurred while generating the report. Please try again later.'], 500);
        }
    }

}
