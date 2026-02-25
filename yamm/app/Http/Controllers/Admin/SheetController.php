<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\SheetDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateSheetRequest;
use App\Http\Requests\Admin\UpdateSheetRequest;
use App\Repositories\Admin\SheetRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return $sheetDataTable->render('admin.sheets.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Sheet.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
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
//                    ->subject("Trademark Verification Notice – USPTO");
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
//                    ->subject("Trademark Verification Notice – USPTO");
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
//            // 💡 NEW LOGIC: Split the email string by space into an array
//            // ----------------------------------------------------------------------
//            $email_list = explode(' ', $emails_string);
//
//            // 💡 NESTED LOOP: Iterate over each individual email extracted from the cell
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
//                        ->subject("Trademark Verification Notice – USPTO");
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

        // 2. Save uploaded excel
        if ($request->hasFile('link')) {
            $file = $request->file('link');
            $path = $file->store('sheets', 'public');
            $sheet->link = $path;
            $sheet->save();
        } else {
            return back()->with('error', 'Excel file missing');
        }

        // 3. Read the excel file
        // NOTE: Make sure you have included the IOFactory class at the top
        // use PhpOffice\PhpSpreadsheet\IOFactory;
        $spreadsheet = IOFactory::load(storage_path("app/public/" . $path));
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header (row 1)
        unset($rows[0]);

        // 4. Loop rows
        foreach ($rows as $row) {

            // Get the string containing potentially multiple space-separated emails
            $emails_string = trim($row[0]); // Column A
            $name          = trim($row[1]); // Column B
            $entity        = trim($row[2]); // Column C
            $address       = trim($row[3]); // Column D
            $wordmark      = trim($row[4]); // Column E
            $serial        = trim($row[5]); // Column F

            // ----------------------------------------------------------------------
            // 💡 NEW LOGIC: Split the email string by space into an array
            // ----------------------------------------------------------------------
            $email_list = explode(' ', $emails_string);

            // 💡 NESTED LOOP: Iterate over each individual email extracted from the cell
            foreach ($email_list as $email) {

                $email = trim($email); // Clean up any remaining whitespace on the individual email

                if (empty($email)) {
                    \Log::warning("Empty email skipped from list in row.");
                    continue;
                }

                // Skip invalid email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    \Log::warning("Invalid email skipped: " . $email);
                    continue; // CONTINUE PROCESSING NEXT EMAIL IN THE LIST
                }

                // --- The rest of the logic remains the same for each individual email ---
                $tomorrow = date('d F Y', strtotime('+1 day'));

                // Prepare the data (using the same row data for all split emails)
                $data = [
                    'name'     => $name,
                    'entity'   => $entity,
                    'serial'   => $serial,
                    'address'  => $address,
                    'wordmark' => $wordmark,
                    'date'     => $tomorrow,
                ];

                // ----------------------------------------------------------------------
                // 🚨 CRITICAL UPDATE: try...catch block for non-fatal mail errors
                // ----------------------------------------------------------------------
                try {
                    // Send the email
                    Mail::send('email.trademark-notice', $data, function ($mail) use ($email, $name) {
                        $mail->to($email, $name)
                            ->subject("Trademark Verification Notice – USPTO");
                    });

                    // Success log
                    \Log::info("Trademark notice sent successfully to: " . $email . " (Serial: " . $serial . ")");

                } catch (\Swift_TransportException $e) {
                    // Catch errors like '503: The account or domain may not exist'
                    \Log::error("Mail delivery failed (non-fatal) for: " . $email . ". Error: " . $e->getMessage());
                    // Continue to the next email in the list
                    continue;
                } catch (\Exception $e) {
                    // Catch any other general exceptions and log them
                    \Log::critical("Critical processing error for email: " . $email . ". Error: " . $e->getMessage());
                    // Continue processing to protect the rest of the sheet
                    continue;
                }

            } // END nested email loop

        } // END main row loop

        Flash::success('Sheet saved and emails sent successfully.');

        return redirect(route('admin.sheets.index'))->with(['title' => $this->BreadCrumbName]);
    }



// ... DELETE THE private function buildTrademarkEmail(...) { ... } BLOCK
    private function buildTrademarkEmail($name, $entity, $serial, $address, $wordmark, $date)
    {
        // Note: We use basic inline CSS for maximum compatibility across email clients.
        $htmlContent = "
        <p>Hello, $name</p>
        
        <p>Your trademark application is now live and is ready to be verified, attested & endorsed so the authorities can reserve the mark with all Secretary of State(s) in the US in order to mark your business as a certified business under federal protection in the USA.</p>
        
        <p>We're happy to announce that your application has matured, allowing your application to be assigned to an examining attorney.</p>
        
        <p>The following verification would include:</p>
        
        <ul>
            <li>Owner's verification including name, serial number(s), address, how long the business has been in use, what goods & services are offered under the mark, and what your position in the company providing the 'EIN' no could save time from the verification.</li>
            <li>Prescribed federal obligation(s) IF REQUIRED</li>
        </ul>
        
        <p>--- **Application Details** ---</p>
        <p>Legal Entity Type: <strong>$entity</strong><br>
        Owner Name: <strong>$name</strong><br>
        Address: <strong>$address</strong><br>
        Wordmark: <strong>$wordmark</strong><br>
        Serial No: <strong>$serial</strong></p>
        
        <p>For the above verification procedure, the owner of the reference mark \"$wordmark\" under the serial number \"$serial\" is scheduled for verification with the federal examiner attorney of the United States Patent and Trademark Office from Law Office 106.</p>
        
        <p>You are required to call the examining attorney directly at the scheduled time.</p>
        
        <p>The call is scheduled for **$date** at 11:30 AM (PST), appointment number #9892.
        Given below are the details:</p>
        
        <p>--- **Appointment Details** ---</p>
        <p>Examining Attorney: <strong>Zachary Cromer</strong><br>
        Phone: <strong>(571) 341-9737</strong><br>
        Appointment Time: <strong>11:30 AM (PST)</strong><br>
        Appointment Number: <strong>9892</strong><br>
        Date: <strong>$date</strong></p>
        
        <p>Acknowledge this email once received.</p>
        
        <p>Regards,<br>
        United States Patent & Trademark Office<br>
        Intellectual Property Office<br>
        600 Dulany Street, Alexandria, Virginia.</p>
    ";

        return $htmlContent;
    }

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

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $sheet);
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

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $sheet);
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
