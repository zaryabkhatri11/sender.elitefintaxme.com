<?php
namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\SheetDataTable;
use App\Http\Requests\Admin\CreateSheetRequest;
use App\Http\Requests\Admin\UpdateSheetRequest;
use App\Repositories\Admin\SheetRepository;
use App\Http\Controllers\AppBaseController;
use App\Jobs\SendEmailJob;   // ✅ correct import
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Customer;

class SheetController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  SheetRepository */
    private $sheetRepository;

    public function __construct(SheetRepository $sheetRepo)
    {
        $this->sheetRepository = $sheetRepo;
        $this->ModelName = 'sheets';
        $this->BreadCrumbName = 'Sheets';
    }

    /**
     * Display a listing of the Sheet.
     *
     * @param SheetDataTable $sheetDataTable
     * @return Response
     */
    public function index(SheetDataTable $sheetDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        return $sheetDataTable->render('admin.sheets.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Sheet.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName);
        return view('admin.sheets.create')->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Store a newly created Sheet in storage.
     *
     * @param CreateSheetRequest $request
     *
     * @return Response
     */
    //    public function store(CreateSheetRequest $request)
//    {
//        $sheet = $this->sheetRepository->saveRecord($request);
//
//        // 2. Save uploaded excel
//        if ($request->hasFile('link')) {
//            $file = $request->file('link');
//            $path = $file->store('sheets', 'public');
//            $sheet->link = $path;
//            $sheet->save();
//        } else {
//            return back()->with('error', 'Excel file missing');
//        }
//
//        // 3. Read the excel file
//        $spreadsheet = IOFactory::load(storage_path("app/public/" . $path));
//        $worksheet = $spreadsheet->getActiveSheet();
//        $rows = $worksheet->toArray();
//
//        // Remove header (row 1)
//        unset($rows[0]);
//
//        // 4. Loop rows
//        foreach ($rows as $row) {
//
//            $email     = trim($row[0]); // Column A
//            $name      = trim($row[1]); // Column B
//            $entity    = trim($row[2]); // Column C
//            $address   = trim($row[3]); // Column D
//            $wordmark  = trim($row[4]); // Column E
//            $serial    = trim($row[5]); // Column F
//
////            if (empty($email)) continue; // skip invalid rows
//            if (empty($email)) {
//                \Log::warning("Empty email skipped.");
//                continue;
//            }
//
//            // Skip invalid email format
//            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                \Log::warning("Invalid email skipped: " . $email);
//                continue; // CONTINUE PROCESSING NEXT ROW
//            }
//            $tomorrow = date('d F Y', strtotime('+1 day'));
//
//            // Build email body
//            $message = $this->buildTrademarkEmail(
//                $name,
//                $entity,
//                $serial,
//                $address,
//                $wordmark,
//                $tomorrow
//            );
//
//            // Send the email
//            Mail::raw($message, function ($mail) use ($email, $name) {
//                $mail->to($email, $name)
//                    ->subject("Trademark Verification Notice â€“ USPTO");
//            });
//        }
//
//        Flash::success('Sheet saved and emails sent successfully.');
//
//        Flash::success($this->BreadCrumbName . ' saved successfully.');
//        if (isset($request->continue)) {
//            $redirect_to = redirect(route('admin.sheets.create'));
//        } elseif (isset($request->translation)) {
//            $redirect_to = redirect(route('admin.sheets.edit', $sheet->id));
//        } else {
//            $redirect_to = redirect(route('admin.sheets.index'));
//        }
//        return $redirect_to->with([
//            'title' => $this->BreadCrumbName
//        ]);
//    }



    // ...
//    public function store(CreateSheetRequest $request)
//    {
//        $sheet = $this->sheetRepository->saveRecord($request);
//
//        // 2. Save uploaded excel
//        if ($request->hasFile('link')) {
//            $file = $request->file('link');
//            $path = $file->store('sheets', 'public');
//            $sheet->link = $path;
//            $sheet->save();
//        } else {
//            return back()->with('error', 'Excel file missing');
//        }
//
//        // 3. Read the excel file
//        // NOTE: Make sure you have included the IOFactory class at the top
//        // use PhpOffice\PhpSpreadsheet\IOFactory;
//        $spreadsheet = IOFactory::load(storage_path("app/public/" . $path));
//        $worksheet = $spreadsheet->getActiveSheet();
//        $rows = $worksheet->toArray();
//
//        // Remove header (row 1)
//        unset($rows[0]);
//
//        // 4. Loop rows
//        foreach ($rows as $row) {
//
//            $email     = trim($row[0]); // Column A
//            $name      = trim($row[1]); // Column B
//            $entity    = trim($row[2]); // Column C
//            $address   = trim($row[3]); // Column D
//            $wordmark  = trim($row[4]); // Column E
//            $serial    = trim($row[5]); // Column F
//
//            if (empty($email)) {
//                \Log::warning("Empty email skipped.");
//                continue;
//            }
//
//            // Skip invalid email format
//            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                \Log::warning("Invalid email skipped: " . $email);
//                continue; // CONTINUE PROCESSING NEXT ROW
//            }
//            $tomorrow = date('d F Y', strtotime('+1 day'));
//
//            // Prepare the data to be passed to the Blade view
//            $data = [
//                'name'     => $name,
//                'entity'   => $entity,
//                'serial'   => $serial,
//                'address'  => $address,
//                'wordmark' => $wordmark,
//                'date'     => $tomorrow,
//            ];
//
//            // Send the email using the Blade view
//            // The first argument 'emails.trademark-notice' points to the Blade file
//            Mail::send('email.trademark-notice', $data, function ($mail) use ($email, $name) {
//                $mail->to($email, $name)
//                    ->subject("Trademark Verification Notice â€“ USPTO");
//            });
//            \Log::info("Trademark notice sent successfully to: " . $email);
//        }
//
//        Flash::success('Sheet saved and emails sent successfully.');
//
//        return redirect(route('admin.sheets.index'))->with(['title' => $this->BreadCrumbName]);
//    }


    //    public function store(CreateSheetRequest $request)
//    {
//        $sheet = $this->sheetRepository->saveRecord($request);
//
//        // 2. Save uploaded excel
//        if ($request->hasFile('link')) {
//            $file = $request->file('link');
//            $path = $file->store('sheets', 'public');
//            $sheet->link = $path;
//            $sheet->save();
//        } else {
//            return back()->with('error', 'Excel file missing');
//        }
//
//        // 3. Read the excel file
//        // NOTE: Make sure you have included the IOFactory class at the top
//        // use PhpOffice\PhpSpreadsheet\IOFactory;
//        $spreadsheet = IOFactory::load(storage_path("app/public/" . $path));
//        $worksheet = $spreadsheet->getActiveSheet();
//        $rows = $worksheet->toArray();
//
//        // Remove header (row 1)
//        unset($rows[0]);
//
//        // 4. Loop rows
//        foreach ($rows as $row) {
//
//            // Get the string containing potentially multiple space-separated emails
//            $emails_string = trim($row[0]); // Column A
//            $name          = trim($row[1]); // Column B
//            $entity        = trim($row[2]); // Column C
//            $address       = trim($row[3]); // Column D
//            $wordmark      = trim($row[4]); // Column E
//            $serial        = trim($row[5]); // Column F
//
//            // ----------------------------------------------------------------------
//            // ðŸ’¡ NEW LOGIC: Split the email string by space into an array
//            // ----------------------------------------------------------------------
//            $email_list = explode(' ', $emails_string);
//
//            // ðŸ’¡ NESTED LOOP: Iterate over each individual email extracted from the cell
//            foreach ($email_list as $email) {
//
//                $email = trim($email); // Clean up any remaining whitespace on the individual email
//
//                if (empty($email)) {
//                    \Log::warning("Empty email skipped from list in row.");
//                    continue;
//                }
//
//                // Skip invalid email format
//                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                    \Log::warning("Invalid email skipped: " . $email);
//                    continue; // CONTINUE PROCESSING NEXT EMAIL IN THE LIST
//                }
//
//                // --- The rest of the logic remains the same for each individual email ---
//                $tomorrow = date('d F Y', strtotime('+1 day'));
//
//                // Prepare the data (using the same row data for all split emails)
//                $data = [
//                    'name'     => $name,
//                    'entity'   => $entity,
//                    'serial'   => $serial,
//                    'address'  => $address,
//                    'wordmark' => $wordmark,
//                    'date'     => $tomorrow,
//                ];
//
//                // Send the email
//                Mail::send('email.trademark-notice', $data, function ($mail) use ($email, $name) {
//                    $mail->to($email, $name)
//                        ->subject("Trademark Verification Notice â€“ USPTO");
//                });
//                \Log::info("Trademark notice sent successfully to: " . $email . " (Serial: " . $serial . ")");
//            } // END nested email loop
//
//        } // END main row loop
//
//        Flash::success('Sheet saved and emails sent successfully.');
//
//        return redirect(route('admin.sheets.index'))->with(['title' => $this->BreadCrumbName]);
//    }
    public function store(CreateSheetRequest $request)
    {
        $sheet = $this->sheetRepository->saveRecord($request);

        if (!$request->hasFile('link')) {
            return back()->with('error', 'Excel file missing');
        }

        $file = $request->file('link');

        $dir = storage_path('app/uploads');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $fullPath = $dir . '/' . $filename;

        // ✅ move file (NO mime detect, safe)
        $file->move($dir, $filename);

        // save relative path in DB
        $sheet->link = 'uploads/' . $filename;
        $sheet->save();

        // ✅ LOAD ACTUAL FILE (IMPORTANT)
        $spreadsheet = IOFactory::load($fullPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        unset($rows[0]); // remove header
        $totalEmails = 0;

        foreach ($rows as $row) {
            if (empty($row[0]))
                continue; // skip if email is empty

            $email_list = preg_split('/\s+/', trim($row[0]));

            // ✅ 1. Save/Update Customer record (Basic mapping from first email in list if multiple)
            $customer_data = [
                'email' => trim($email_list[0]),
                'phone' => trim($row[1]),
                'owner_name' => trim($row[2]),
                'entity' => trim($row[3]),
                'owner_address' => trim($row[4]),
                'subject_mark' => trim($row[5]),
                'case_number' => trim($row[6]),
                'status' => $request->input('customer_status', 'live'),
            ];

            // Deduplicate by case_number if available, otherwise by email
            $match_key = !empty($customer_data['case_number']) ? ['case_number' => $customer_data['case_number']] : ['email' => $customer_data['email']];

            $customer = Customer::updateOrCreate(
                $match_key,
                $customer_data
            );

            // ✅ 2. Dispatch emails for each email in the cell
            foreach ($email_list as $email) {
                $email = trim($email);
                if (!$email)
                    continue;

                $totalEmails++;

                SendEmailJob::dispatch([
                    'customer_id' => $customer->id,
                    'email' => $email,
                    'name' => $row[2],
                    'entity' => $row[3],
                    'address' => $row[4],
                    'wordmark' => $row[5],
                    'serial' => $row[6],
                    'date' => date('l, F d, Y', strtotime('+1 day')),
                    // Batch form data
                    'examining_attorney' => $request->examining_attorney,
                    'direct_phone' => $request->direct_phone,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'appointment_number' => $request->appointment_number,
                ])->onQueue('emails');
            }
        }

        return response()->json(['status' => true, 'total' => $totalEmails]);
        // rest of your email logic stays SAME
    }




    // ... DELETE THE private function buildTrademarkEmail(...) { ... } BLOCK



    /**
     * Display the specified Sheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.sheets.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $sheet);
        return view('admin.sheets.show')->with(['sheet' => $sheet, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified Sheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.sheets.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName, $this->BreadCrumbName, $sheet);
        return view('admin.sheets.edit')->with(['sheet' => $sheet, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified Sheet in storage.
     *
     * @param  int              $id
     * @param UpdateSheetRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSheetRequest $request)
    {
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.sheets.index'));
        }

        $sheet = $this->sheetRepository->updateRecord($request, $sheet);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.sheets.create'));
        } else {
            $redirect_to = redirect(route('admin.sheets.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified Sheet from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.sheets.index'));
        }

        $this->sheetRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.sheets.index'))->with(['title' => $this->BreadCrumbName]);
    }
}
