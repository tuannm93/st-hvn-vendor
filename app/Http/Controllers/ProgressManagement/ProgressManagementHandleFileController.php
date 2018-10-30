<?php

namespace App\Http\Controllers\ProgressManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProgCorpService;

class ProgressManagementHandleFileController extends Controller
{
    /**
     * @var ProgCorpService
     */
    public $pCorpService;
    /**
     * @param ProgCorpService $pCorpService
     */
    public function __construct(
        ProgCorpService $pCorpService
    ) {
        parent::__construct();
        $this->pCorpService = $pCorpService;
    }

    /**
     * send corp email
     * @author thaihv
     * @param  Request $request
     * @param  integer $pCorpId prog_corps id
     * @param  integer $fileId file id

     * @throws \Exception
     * @return json status and message
     */
    public function sendEmail(Request $request, $pCorpId, $fileId)
    {
        $corpRequestData = $request->all();
        $count = $this->pCorpService->sendEmail($corpRequestData, $fileId);
        if ($count > 0) {
            $saved = $this->pCorpService->updateAfterSendEmail($pCorpId, $count);
            if ($saved) {
                $request->session()->flash('message', __('progress_management.email_send_success', ['corpName' => $corpRequestData['name']]));
                return response()->json(['status' => 200, 'message' => 'send email and save success']);
            }
            $request->session()->flash('message', __('progress_management.email_send_fail', ['corpName' => $corpRequestData['name']]));
            return response()->json(['status' => 500, 'message' => 'save prog info fail']);
        }
        $request->session()->flash('message', __('progress_management.email_send_fail', ['corpName' => $corpRequestData['name']]));
        return response()->json(['status' => 500, 'message' => 'send email fail']);
    }

    /**
     * send corp fax
     * @author thaihv
     * @param  Request $request
     * @param  integer $pCorpId prog_corps id
     * @return json           status and message
     */
    public function sendFax(Request $request, $pCorpId)
    {
        set_time_limit(0);
        $corpRequestData = $request->all();
        $count = $this->pCorpService->sendFax($pCorpId, $corpRequestData);
        if ($count > 0) {
            $saved = $this->pCorpService->updateAfterSendFax($pCorpId, $count);
            if ($saved) {
                $request->session()->flash('message', __('progress_management.fax_send_success', ['corpName' => $corpRequestData['name']]));
                return response()->json(['status' => 200, 'message' => 'send fax and save success']);
            }
            $request->session()->flash('message', __('progress_management.fax_send_fail', ['corpName' => $corpRequestData['name']]));
            return response()->json(['status' => 500, 'message' => 'save prog info fail']);
        }
        $request->session()->flash('message', __('progress_management.fax_send_fail', ['corpName' => $corpRequestData['name']]));
        return response()->json(['status' => 500, 'message' => 'send fax fail']);
    }

    /**
     * send fax and email
     * @param  Request $request
     * @param  integer $pCorpId prog_corps id
     * @param  integer $fileId file id
     * @throws \Exception
     * @return boolean
     */
    public function sendMailFax(Request $request, $pCorpId, $fileId)
    {
        $corpRequestData = $request->all();
        $faxsData = [];
        $faxsData['name'] = $corpRequestData['name'];
        $faxsData['faxs'] = $corpRequestData['faxs'];

        $mailData = [];
        $mailData['name'] = $corpRequestData['name'];
        $mailData['emails'] = $corpRequestData['emails'];

        // send email

        $countEmail = $this->pCorpService->sendEmail($mailData, $fileId);
        $success = true;
        $message = '';
        if ($countEmail > 0) {
            $saved = $this->pCorpService->updateAfterSendEmail($pCorpId, $countEmail);
            if ($saved) {
                $message .= __('progress_management.email_send_success', ['corpName' => $mailData['name']]) . '<br />';
            } else {
                $success = false;
                $message .= __('progress_management.email_send_fail', ['corpName' => $mailData['name']]) . '<br />';
            }
        }
        $countFax = $this->pCorpService->sendFax($pCorpId, $faxsData);
        if ($countFax > 0) {
            $saved = $this->pCorpService->updateAfterSendFax($pCorpId, $countFax);
            if ($saved) {
                $message .= __('progress_management.fax_send_success', ['corpName' => $faxsData['name']]) . '<br />';
            } else {
                $success = false;
                $message .= __('progress_management.fax_send_fail', ['corpName' => $faxsData['name']]) . '<br />';
            }
        }
        $request->session()->flash('message', $message);
        if ($success) {
            return response()->json(['status' => 200, 'message' => 'sent and update successfully']);
        }
        return response()->json(['status' => 500, 'message' => 'sent and update failed']);
    }

    /**
     * send bulk email
     * @author thaihv
     * @param  Request $request
     * @param  integer $fileId
     * @throws \Exception
     * @return json
     */
    public function sendMultipleEmail(Request $request, $fileId)
    {
        set_time_limit(0);
        $dataMail = $request->get('data');
        $total = 0;
        foreach ($dataMail as $val) {
            $pCorpId = $val['pCorpId'];
            $mails = [
                'name' => $val['name'],
                'emails' => $val['emails']
            ];

            $count = $this->pCorpService->sendEmail($mails, $fileId);
            if ($count > 0) {
                $total += $count;
                $saved = $this->pCorpService->updateAfterSendEmail($pCorpId, $count);
                if ($saved) {
                    $request->session()->flash('message', __('progress_management.sent_bulk_mail'));
                }
            }
        }
        return response()->json(['status' => '200', 'message' => 'total: ' . $total]);
    }

    /**
     * send bulk email fax
     * @author thaihv
     * @param  Request $request
     * @return json
     */
    public function sendMultipleFax(Request $request)
    {
        set_time_limit(0);
        $dataFax = $request->get('data');
        $total = 0;
        foreach ($dataFax as $val) {
            $pCorpId = $val['pCorpId'];
            $faxs = [
                'name' => $val['name'],
                'faxs' => $val['faxs']
            ];

            $count = $this->pCorpService->sendFax($pCorpId, $faxs);
            if ($count > 0) {
                $total += $count;
                $saved = $this->pCorpService->updateAfterSendFax($pCorpId, $count);
                if ($saved) {
                    $request->session()->flash('message', __('progress_management.sent_bulk_fax'));
                }
            }
        }
        return response()->json(['status' => '200', 'message' => 'total: ' . $total]);
    }

    /**
     * send bulk email and fax
     * @author thaihv
     * @param  Request $request
     * @param  integer $fileId
     * @throws \Exception
     * @return json
     */
    public function sendMultipleMailFax(Request $request, $fileId)
    {
        set_time_limit(0);
        $dataFax = $request->get('faxs');
        $dataMail = $request->get('mails');
        $totalMail = 0;
        $totalFax = 0;
        // send fax data
        $messageMail = '';
        $messageFax = '';
        foreach ($dataFax as $val) {
            $pCorpId = $val['pCorpId'];
            $faxs = [
                'name' => $val['name'],
                'faxs' => $val['faxs']
            ];

            $count = $this->pCorpService->sendFax($pCorpId, $faxs);
            if ($count > 0) {
                $totalFax += $count;
                $saved = $this->pCorpService->updateAfterSendFax($pCorpId, $count);
                if ($saved) {
                    $messageMail = __('progress_management.sent_bulk_fax') . '<br />';
                }
            }
        }
        // send mail data
        foreach ($dataMail as $val) {
            $pCorpId = $val['pCorpId'];
            $mails = [
                'name' => $val['name'],
                'emails' => $val['emails']
            ];

            $count = $this->pCorpService->sendEmail($mails, $fileId);
            if ($count > 0) {
                $totalMail += $count;
                $saved = $this->pCorpService->updateAfterSendEmail($pCorpId, $count);
                if ($saved) {
                    $messageFax = __('progress_management.sent_bulk_mail') . '<br />';
                }
            }
        }
        $request->session()->flash('message', $messageMail . $messageFax);
        return response()->json(['status' => '200', 'message' => 'totalMail: ' . $totalMail . '. totalFax: ' . $totalFax]);
    }

    /**
     * [outputCSV description]
     * @author thaihv
     * @param  integer $fileId prog_corps id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function outputCSV($fileId)
    {
        set_time_limit(0);
        $file = $this->pCorpService->outputCSV($fileId);
        if (!$file) {
            return view('errors.404');
        }
        return $file;
    }

    /**
     * @param $pCorpId
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function outputFileCSV($pCorpId)
    {
        set_time_limit(0);
        $file = $this->pCorpService->outputCSV($pCorpId, 'file');
        if (!$file) {
            return view('errors.404');
        }
        return $file;
    }

    /**
     * [outputPDF description]
     * @author thaihv
     * @param $pCorpId
     * @return file
     */
    public function outputPDF($pCorpId)
    {
        set_time_limit(0);
        ini_set("pcre.backtrack_limit", "5000000");
        $type = 'D'; // D will do download, F for storage
        $file = $this->pCorpService->outputPDF($pCorpId, $type);
        if (!$file) {
            return view('errors.404');
        }
        return $file;
    }
}
