<?php

namespace App\Validators;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\MCorp;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend(
            'globalrule',
            function ($attribute, $value, $parameters) {
                // implement global rule
                return true;
            }
        );

        Validator::extend(
            'AffiliationAddValidateMailRequired',
            function ($attribute, $value, $parameters, $validator) {
                $coordinationMethod = [
                    MCorp::METHOD_NUM_1,
                    MCorp::METHOD_NUM_2,
                    MCorp::METHOD_NUM_6,
                    MCorp::METHOD_NUM_7,
                ];
                if (in_array($parameters[1], $coordinationMethod) && !$parameters[0] && !$value) {
                    return false;
                }
                return true;
            }
        );
        Validator::extend(
            'AffiliationAddValidateMailFormat',
            function ($attribute, $value, $parameters, $validator) {
                if ($value) {
                    if (strpos($value, ';') !== false) {
                        $arrayMail = explode(";", $value);
                        foreach ($arrayMail as $email) {
                            if(preg_match('/^[[:ascii:]]+$/', $email) && strpos($email, '@') !== false){
                                continue;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        if(preg_match('/^[[:ascii:]]+$/', $value) && strpos($value, '@') !== false){
                            return true;
                        } else {
                            return false;
                        }
                    }
                }

                return true;
            }
        );
        Validator::extend(
            'TextAreaMaxLength',
            function ($attribute, $value, $parameters, $validator) {
                if ($value) {
                    $value = str_replace("\r\n", "\n", $value);
                    if (mb_strlen($value) > $parameters[0]) {
                        return false;
                    }
                }
                return true;
            }
        );
    }

    /**
     * @return void
     */
    public function register()
    {
        //
    }
}
