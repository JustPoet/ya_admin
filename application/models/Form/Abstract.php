<?php
/**
 * Abstract.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/12 14:58
 * 修改记录:
 *
 * $Id$
 */

abstract class Form_AbstractModel
{
    /**
     * 表单字段
     *
     * @var array
     */
    protected $fields = [];

    /**
     * 构造方法
     *
     * @param array $data
     *
     * @throws Exception
     */
    public function __construct($data = []) {
        if (empty($this->fields)) {
            throw new Exception("表单字段配置为空");
        }
        $this->setFieldDefaultData();
        if ($data) {
            $this->setData($data);
        }
        $this->validateFields();
    }

    /**
     * 设置字段的值
     *
     * @param array $data
     */
    public function setData($data) {
        foreach ($this->fields as $k => $v) {
            if (!array_key_exists($k, $data)) {
                continue;
            }
            if (is_string($data[$k])) {
                //空字符串并且该字段有设置默认值则不进行设置
                if (strlen(trim($data[$k])) == 0 &&
                    isset($this->fields[$k]['default']) &&
                    strlen($this->fields[$k]['default']) > 0) {
                    continue;
                }
                $this->fields[$k]['value'] = trim($data[$k]);
                continue;
            }
            $this->fields[$k]['value'] = $data[$k];
        }
    }

    /**
     * 设置字段的默认数据
     */
    private function setFieldDefaultData() {
        foreach ($this->fields as $k => $v) {
            $this->fields[$k]["is_validate"] = true;
            if (!isset($v["require"])) {
                $this->fields[$k]["require"] = true;
            }
            if (!isset($v["message"])) {
                $this->fields[$k]["message"] = $this->fields[$k]['label'] . "错误";
            }
            if (isset($v["default"])) {
                $this->fields[$k]['value'] = $v["default"];
            }
        }
    }

    /**
     * 校验字段格式设置是否准确
     *
     * @throws Exception
     */
    private function validateFields() {
        if (!is_array($this->fields)) {
            throw new Exception("fields is not array");
        }
        foreach ($this->fields as $k => $v) {
            if (!isset($v["label"])) {
                throw new Exception("field " . $k . " label is not set");
            }
            if (!isset($v["name"])) {
                throw new Exception("field " . $k . " name is not set");
            }
            if ($k !== $v["name"]) {
                throw new Exception("field " . $k . " name is not same");
            }
            if (isset($v["validate"])) {
                if (!is_array($v["validate"])) {
                    throw new Exception("field " . $k . " validate is not array");
                }
                foreach ($v["validate"] as $validate) {
                    if (!isset($validate["type"])) {
                        throw new Exception("field " . $k . " validate type is not set");
                    }
                    if ($validate['type'] == "set" &&
                        (!isset($validate['set']) || !is_array($validate['set']))) {
                        throw new Exception("field " . $k . " validate set is not set");
                    }
                }
            }
        }
    }

    /**
     * 校验所有字段的值
     *
     * @return boolean
     */
    public function validate() {
        foreach ($this->fields as $fieldName => $field) {
            if (!$field["require"]) {
                if (!isset($field["value"])) {
                    continue;
                }
                if (is_string($field["value"]) && strlen($field["value"]) == 0) {
                    continue;
                }
                if (is_array($field["value"]) && !$field["value"]) {
                    continue;
                }
            }
            if ($field["require"] && !isset($field["value"])) {
                $this->fields[$fieldName]["is_validate"] = false;
                continue;
            }
            if ($field["require"] &&
                !in_array($field["value"], array(0, "0"), true) &&
                empty($field["value"])) {
                $this->fields[$fieldName]["is_validate"] = false;
                continue;
            }

            if (is_string($field["value"]) && $this->validateHtmlTag($field["value"])) {
                $this->fields[$fieldName]["is_validate"] = false;
                continue;
            }

            if (!empty($field['validate'])) {
                foreach ($field['validate'] as $validate) {
                    $validateMethodName = 'validateFieldValue' . $validate["type"];
                    if (method_exists($this, $validateMethodName)) {
                        $this->$validateMethodName($fieldName, $validate);
                    }
                }
            }
            //检测各个字段自己的校验方法
            $methodName = 'validate' . ucfirst(preg_replace_callback('/_\w/i'
                    , create_function('$matches', 'return strtoupper(ltrim($matches[0],"_"));')
                    , $fieldName));
            if (method_exists($this, $methodName)) {
                if (!$this->$methodName()) {
                    $this->fields[$fieldName]["is_validate"] = false;
                }
            }
        }
        foreach ($this->fields as $field) {
            if (!$field["is_validate"]) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取字段的值
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getFieldValue($fieldName = null) {
        if (!$fieldName) { //获取所有字段的值
            $fieldsValue = array();
            foreach ($this->fields as $field) {
                if (isset($field['value'])) {
                    $fieldsValue[$field['name']] = $field['value'];
                } else {
                    $fieldsValue[$field['name']] = null;
                }
            }
            return $fieldsValue;
        }
        foreach ($this->fields as $field) {
            if ($field['name'] == $fieldName && isset($field['value'])) {
                return $field['value'];
            }
        }
        return null;
    }

    /**
     * 获取没有校验过的字段提示信息
     *
     * @param null $fieldName
     *
     * @return array
     */
    public function getMessages($fieldName = null) {
        if ($fieldName) {
            if ($this->fields[$fieldName]['is_validate']) {
                return null;
            }
            return $this->fields[$fieldName]['message'];
        }

        $fieldsMessage = array();
        foreach ($this->fields as $field) {
            if (!$field['is_validate']) {
                $fieldsMessage[$field['name']] = $field['message'];
            }
        }
        return $fieldsMessage;
    }

    /**
     * 设置字段的属性值
     *
     * @param string $fieldName
     * @param array $attrs
     */
    public function setFiledsAttr($fieldName, $attrs) {
        foreach ($attrs as $k => $v) {
            $this->fields[$fieldName][$k] = $v;
        }
    }

    /**
     * 获取字段的属性值
     *
     * @param string       $fieldName
     * @param array|string $attrs
     *
     * @return array|null
     * @throws Exception
     */
    public function getFieldAttrs($fieldName, $attrs) {
        $this->validateFieldExist($fieldName);
        if (is_string($attrs)) {
            if (isset($this->fields[$fieldName][$attrs])) {
                return $this->fields[$fieldName][$attrs];
            }
            return null;
        }

        $return = array();
        foreach ($attrs as $attr) {
            if (isset($this->fields[$fieldName][$attr])) {
                $return[$attr] = $this->fields[$fieldName][$attr];
                continue;
            }
            $return[$attr] = null;
        }
        return $return;
    }

    /**
     * 设置字段是否需要校验
     *
     * @param string  $fieldName
     * @param boolean $isRequire
     *
     * @throws Exception
     */
    public function setRequire($fieldName, $isRequire) {
        $this->validateFieldExist($fieldName);
        $this->setFiledsAttr($fieldName, array('require' => $isRequire));
    }

    /**
     * 设置字段的提示信息
     *
     * @param string $fieldName
     * @param string $message
     *
     * @throws Exception
     */
    public function setFieldMessage($fieldName, $message) {
        $this->validateFieldExist($fieldName);
        $this->fields[$fieldName]['message'] = $message;
    }

    /**
     * 校验字段是否存在
     *
     * @param string $fieldName
     * @return boolean
     * @throws Exception
     */
    private function validateFieldExist($fieldName) {
        if (!array_key_exists($fieldName, $this->fields)) {
            throw new Exception("field " . $fieldName . " is not exist");
        }
        return true;
    }

    /**
     * 移除字段
     *
     * @param string $fieldName
     */
    public function removeField($fieldName) {
        if (isset($this->fields[$fieldName])) {
            unset($this->fields[$fieldName]);
        }
    }

    /**
     * 获取所有字段
     *
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * 字符串校验器
     *
     * @return boolean
     * @throws Exception
     */
    private function validateFieldValueString($fieldName, $validate) {
        $field   = $this->fields[$fieldName];
        $options = array("value" => $field["value"]);
        if (isset($validate["min"])) {
            $options["min"] = $validate["min"];
        }
        if (isset($validate["max"])) {
            $options["max"] = $validate["max"];
        }
        if ($this->validateLength($options)) {
            $this->fields[$fieldName]["is_validate"] = true;
            return true;
        }
        if (isset($validate["msg"])) {
            $this->setFieldMessage($fieldName, $validate["msg"]);
        }
        $this->fields[$fieldName]["is_validate"] = false;
        return false;
    }

    /**
     * 整形校验器
     *
     * @return boolean
     */
    private function validateFieldValueInt($fieldName, $validate) {
        $field   = $this->fields[$fieldName];
        $options = array("value" => $field["value"]);
        if (isset($validate["min"])) {
            $options["min"] = $validate["min"];
        }
        if (isset($validate["max"])) {
            $options["max"] = $validate["max"];
        }
        if ($this->validateInt($options)) {
            $this->fields[$fieldName]["is_validate"] = true;
            return true;
        }
        if (isset($validate["msg"])) {
            $this->setFieldMessage($fieldName, $validate["msg"]);
        }
        $this->fields[$fieldName]["is_validate"] = false;
        return false;
    }

    /**
     * 小数校验器
     *
     * @return boolean
     * @throws Exception
     */
    private function validateFieldValueFloat($fieldName, $validate) {
        $field   = $this->fields[$fieldName];
        $options = array("value" => $field["value"]);
        if (isset($validate["min"])) {
            $options["min"] = $validate["min"];
        }
        if (isset($validate["max"])) {
            $options["max"] = $validate["max"];
        }
        if ($this->validateFloat($options)) {
            $this->fields[$fieldName]["is_validate"] = true;
            return true;
        }
        if (isset($validate["msg"])) {
            $this->setFieldMessage($fieldName, $validate["msg"]);
        }
        $this->fields[$fieldName]["is_validate"] = false;
        return false;
    }

    /**
     * 日期校验器
     *
     * @return boolean
     * @throws Exception
     */
    private function validateFieldValueDate($fieldName, $validate) {
        $field   = $this->fields[$fieldName];
        $options = array("value" => $field["value"]);
        if (isset($validate["formats"])) {
            $options["formats"] = $validate["formats"];
        }
        if ($this->validateDate($options)) {
            $this->fields[$fieldName]["is_validate"] = true;
            return true;
        }
        if (isset($validate["msg"])) {
            $this->setFieldMessage($fieldName, $validate["msg"]);
        }
        $this->fields[$fieldName]["is_validate"] = false;
        return false;
    }

    /**
     * 集合校验器
     *
     * @return boolean
     * @throws Exception
     */
    private function validateFieldValueSet($fieldName, $validate) {
        $field = $this->fields[$fieldName];
        if (in_array($field['value'], $validate['set'])) {
            $this->fields[$fieldName]["is_validate"] = true;
            return true;
        }
        if (isset($validate["msg"])) {
            $this->setFieldMessage($fieldName, $validate["msg"]);
        }
        $this->fields[$fieldName]["is_validate"] = false;
        return false;
    }

    /**
     * 校验字符串长度
     *
     * @param array $options
     * @return boolean
     */
    protected function validateLength($options) {
        $length = mb_strlen($options['value']);
        if (isset($options['min'])) {
            if ($length < $options['min']) {
                return false;
            }
        }
        if (isset($options['max'])) {
            if ($length > $options['max']) {
                return false;
            }
        }
        return true;
    }

    /**
     * 校验整形数字
     *
     * @param array $options
     * @return boolean
     */
    protected function validateInt($options) {
        $num = $options['value'];
        if (isset($options['min']) && isset($options['max'])) {
            $int_options = array("options" => array("min_range" => $options['min'], "max_range" => $options['max']));
        } else if (isset($options['min'])) {
            $int_options = array("options" => array("min_range" => $options['min']));
        } else if (isset($options['max'])) {
            $int_options = array("options" => array("max_range" => $options['max']));
        }
        $result = filter_var($num, FILTER_VALIDATE_INT, $int_options);
        if ($result === FALSE) {
            return false;
        }
        return true;
    }

    /**
     * 校验小数
     *
     * @param array $options
     * @return boolean
     */
    protected function validateFloat($options) {
        $result = filter_var($options["value"], FILTER_VALIDATE_FLOAT);
        if ($result === false) {
            return false;
        }
        if (isset($options['min']) && $options["value"] < $options["min"]) {
            return false;
        }
        if (isset($options['max']) && $options["value"] > $options["max"]) {
            return false;
        }
        return true;
    }

    /**
     * 校验日期格式是否正确
     *
     * @param array $options
     * @return boolean
     */
    protected function validateDate($options) {
        $date    = $options['value'];
        $formats = $options["formats"];

        $unixTime = strtotime($date);
        if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
            return false;
        }

        //校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }

        return false;
    }

    /**
     * 校验是否包含html和php标签
     *
     * @param string $str
     * @return boolean
     */
    public function validateHtmlTag($str) {
        return $str != strip_tags($str);
    }
}