<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BankCredential
 *
 * @property int $id
 * @property int $enable
 * @property int $user_id
 * @property string|null $currency
 * @property string|null $details
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankCredential whereUserId($value)
 */
	class BankCredential extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string|null $images
 * @property string $name
 * @property string|null $email
 * @property string|null $phone_number
 * @property int $purchases
 * @property float $balance
 * @property string|null $location
 * @property string|null $city
 * @property string|null $zip
 * @property string|null $country
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomerInvoice[] $invoices
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePurchases($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereZip($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerInvoice
 *
 * @property int $id
 * @property string $token
 * @property int $user_id
 * @property string|null $transaction_id
 * @property string $status
 * @property int $customer_id
 * @property string $allow_partial
 * @property string|null $payment_processor
 * @property string $items
 * @property float|null $discount
 * @property float $sub_total
 * @property float $tax
 * @property float $total
 * @property string|null $tax_ids
 * @property string|null $product_ids
 * @property string|null $note
 * @property float $amount_paid
 * @property string|null $repeat_data
 * @property string|null $repeat_until
 * @property string|null $last_repeat
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereAllowPartial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereLastRepeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice wherePaymentProcessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereProductIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereRepeatData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereRepeatUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereTaxIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoice whereUserId($value)
 */
	class CustomerInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerInvoiceConfiguration
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $business_name
 * @property string|null $business_id
 * @property string|null $business_logo
 * @property string|null $business_phone
 * @property string|null $business_location
 * @property string|null $business_zip
 * @property string|null $business_city
 * @property string|null $business_country
 * @property string|null $business_legal_terms
 * @property string $currency_locale
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessLegalTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereBusinessZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereCurrencyLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceConfiguration whereUserId($value)
 */
	class CustomerInvoiceConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerInvoiceTransaction
 *
 * @property int $id
 * @property string|null $details
 * @property int $user_id
 * @property int $customer_id
 * @property int $customer_invoice_id
 * @property float|null $amount
 * @property string|null $processor
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\CustomerInvoice $customer_invoice
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereCustomerInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereProcessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerInvoiceTransaction whereUserId($value)
 */
	class CustomerInvoiceTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailConfiguration
 *
 * @property int $id
 * @property string|null $from_address
 * @property string|null $from_name
 * @property string|null $reply_to_address
 * @property string|null $reply_to_name
 * @property int $user_id
 * @property string|null $header
 * @property string|null $header_url
 * @property string|null $subcopy
 * @property string|null $footer
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereFromAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereFromName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereHeaderUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereReplyToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereReplyToName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereSubcopy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailConfiguration whereUserId($value)
 */
	class EmailConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Expense
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $note
 * @property int|null $expense_category_id
 * @property int $user_id
 * @property string|null $expense_date
 * @property float|null $amount
 * @property string|null $payment_mode
 * @property string|null $payment_reference
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ExpenseCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereExpenseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereExpenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense wherePaymentMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereUserId($value)
 */
	class Expense extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExpenseCategory
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Expense[] $expenses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpenseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpenseCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpenseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpenseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpenseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpenseCategory whereUserId($value)
 */
	class ExpenseCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property int $user_id
 * @property string $transaction_id
 * @property string|null $note
 * @property string $items
 * @property int $total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUserId($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentSetting
 *
 * @property int $id
 * @property int $amount_init
 * @property int $amount_inc
 * @property int $amount_max
 * @property string|null $business_name
 * @property string|null $business_id
 * @property string|null $business_logo
 * @property string|null $business_phone
 * @property string|null $business_location
 * @property string|null $business_zip
 * @property string|null $business_city
 * @property string|null $business_country
 * @property string|null $business_legal_terms
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereAmountInc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereAmountInit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereAmountMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessLegalTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereBusinessZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentSetting whereUpdatedAt($value)
 */
	class PaymentSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaypalCredential
 *
 * @property int $id
 * @property int $enable
 * @property int $user_id
 * @property string|null $currency
 * @property string $mode
 * @property string|null $client_id
 * @property string|null $client_secret
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaypalCredential whereUserId($value)
 */
	class PaypalCredential extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PosConfiguration
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $header
 * @property string|null $footer
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosConfiguration whereFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosConfiguration whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosConfiguration whereUserId($value)
 */
	class PosConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PosTransaction
 *
 * @property int $id
 * @property string|null $details
 * @property int $user_id
 * @property int $customer_id
 * @property int|null $customer_invoice_id
 * @property string|null $items
 * @property string|null $product_ids
 * @property float|null $discount
 * @property float|null $sub_total
 * @property float|null $total
 * @property float|null $tax
 * @property string|null $tax_ids
 * @property string|null $note
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\CustomerInvoice|null $customer_invoice
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereCustomerInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereProductIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereTaxIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PosTransaction whereUserId($value)
 */
	class PosTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string|null $barcode_type
 * @property string|null $barcode
 * @property string|null $weight
 * @property string|null $brand
 * @property float $cost
 * @property int $sales
 * @property float $price
 * @property string|null $taxes
 * @property string|null $images
 * @property string $description
 * @property int|null $product_category_id
 * @property int|null $product_unit_id
 * @property int $quantity
 * @property string $track
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ProductCategory|null $category
 * @property-read \App\Models\ProductUnit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereBarcodeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTrack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereWeight($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductCategory
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereUserId($value)
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductCoupon
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property float $discount
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $type
 * @property string $product_categories
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereProductCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCoupon whereUserId($value)
 */
	class ProductCoupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductTax
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property float $amount
 * @property string $type
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductTax whereUserId($value)
 */
	class ProductTax extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductUnit
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $base
 * @property int $value
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereValue($value)
 */
	class ProductUnit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Profile
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $location
 * @property string|null $bio
 * @property string|null $avatar
 * @property int $avatar_status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereAvatarStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereUserId($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SaleReport
 *
 * @property int $id
 * @property int $user_id
 * @property float $total
 * @property int $purchases
 * @property int $profit
 * @property float $tax
 * @property string $date
 * @property int|null $pos_transaction_id
 * @property int|null $customer_id
 * @property int|null $customer_invoice_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\CustomerInvoice|null $customer_invoice
 * @property-read \App\Models\PosTransaction|null $pos_transaction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereCustomerInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport wherePosTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport wherePurchases($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleReport whereUserId($value)
 */
	class SaleReport extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Setting
 *
 * @property string $site_name
 * @property string $site_name_abbr
 * @property string $site_title
 * @property string $site_desc
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $instagram_url
 * @property string|null $google_plus_url
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $logo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereGooglePlusUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereSiteDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereSiteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereSiteNameAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereSiteTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereUpdatedAt($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Social
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $social_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereUserId($value)
 */
	class Social extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StripeCredential
 *
 * @property int $id
 * @property int $enable
 * @property int $user_id
 * @property string|null $currency
 * @property string|null $secret_key
 * @property string|null $public_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StripeCredential whereUserId($value)
 */
	class StripeCredential extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Supplier
 *
 * @property int $id
 * @property string|null $images
 * @property string $name
 * @property string $company
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $gst_number
 * @property string|null $location
 * @property string|null $city
 * @property string|null $zip
 * @property string|null $country
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereGstNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereZip($value)
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string $password
 * @property string $token
 * @property string $auto_renewal
 * @property string|null $signup_ip_address
 * @property string|null $confirmation_ip_address
 * @property string|null $social_signup_ip_address
 * @property string|null $admin_signup_ip_address
 * @property string|null $updated_ip_address
 * @property string|null $last_login_ip_address
 * @property string|null $deleted_ip_address
 * @property string|null $deleted_reason
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property int $verified
 * @property string|null $verification_token
 * @property-read \App\Models\BankCredential $bank_credential
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomerInvoiceTransaction[] $customer_invoice_transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomerInvoice[] $customer_invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Customer[] $customers
 * @property-read \App\Models\EmailConfiguration $email_configuration
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExpenseCategory[] $expense_categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Expense[] $expenses
 * @property-read \App\Models\CustomerInvoiceConfiguration $invoice_configuration
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\PaypalCredential $paypal_credential
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \App\Models\PosConfiguration $pos_configuration
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PosTransaction[] $pos_transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductCategory[] $product_categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductCoupon[] $product_coupons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTax[] $product_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductUnit[] $product_units
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\Profile $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Profile[] $profiles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleReport[] $sale_reports
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $social
 * @property-read \App\Models\StripeCredential $stripe_credential
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gerardojbaez\Laraplans\Models\PlanSubscription[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Supplier[] $suppliers
 * @property-read \Illuminate\Database\Eloquent\Collection|\Trexology\Pointable\Models\Transaction[] $transactions
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAdminSignupIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAutoRenewal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereConfirmationIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastLoginIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSignupIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSocialSignupIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 */
	class User extends \Eloquent {}
}

