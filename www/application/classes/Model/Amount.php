<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Amount
 */
class Model_Amount extends Base_Model
{

    /**
     * Returns array of table relations
     *
     * @return array
     */
    public function relations()
    {
        return array(
            'access_rules' => array(
                self::BELONGS_TO,
                'Model_User',
                'user_id',
                'id'
            ),
        );
    }

    /**
     * Return array of readable labels for table columns
     *
     * @return array
     */
    public function labels()
    {
        return array(
            'id' => tr('ID'),
            'user_id' => tr('Login'),
            'amount' => tr('Amount'),
        );
    }

    /**
     * Returns array of validation rules
     *
     * @return array
     */
    public function rules()
    {
        return array(
            'user_id' => array(
                'not_empty',
            ),
            'user_id' => array(
                'amount',
            ),
        );
    }
}
