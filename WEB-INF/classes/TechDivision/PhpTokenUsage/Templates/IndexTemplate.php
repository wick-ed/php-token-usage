<?php

/**
 * TechDivision\PhpTokenUsage\Templates\IndexTemplate
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PhpTokenUsage\Templates;

use TechDivision\PhpTokenUsage\Templates\AbstractTemplate;

/**
 * @package     TechDivision\Example
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Johann Zelger <j.zelger@techdivision.com>
 */

class IndexTemplate extends AbstractTemplate
{

    /**
     * Some properties for template
     *
     * @var string
     */
    protected $webappName;
    protected $baseUrl;
    protected $projects;
    protected $tokens = array(
        T_ABSTRACT => 'T_ABSTRACT',
        T_AND_EQUAL => 'T_AND_EQUAL',
        T_ARRAY => 'T_ARRAY',
        T_ARRAY_CAST => 'T_ARRAY_CAST',
        T_AS => 'T_AS',
        T_BAD_CHARACTER => 'T_BAD_CHARACTER',
        T_BOOLEAN_AND => 'T_BOOLEAN_AND',
        T_BOOLEAN_OR => 'T_BOOLEAN_OR',
        T_BOOL_CAST => 'T_BOOL_CAST',
        T_BREAK => 'T_BREAK',
        T_CASE => 'T_CASE',
        T_CATCH => 'T_CATCH',
        T_CHARACTER => 'T_CHARACTER',
        T_CLASS => 'T_CLASS',
        T_CLASS_C => 'T_CLASS_C',
        T_CLONE => 'T_CLONE',
        T_CLOSE_TAG => 'T_CLOSE_TAG',
        T_COMMENT => 'T_COMMENT',
        T_CONCAT_EQUAL => 'T_CONCAT_EQUAL',
        T_CONST => 'T_CONST',
        T_CONSTANT_ENCAPSED_STRING => 'T_CONSTANT_ENCAPSED_STRING',
        T_CONTINUE => 'T_CONTINUE',
        T_CURLY_OPEN => 'T_CURLY_OPEN',
        T_DEC => 'T_DEC',
        T_DECLARE => 'T_DECLARE',
        T_DEFAULT => 'T_DEFAULT',
        T_DIR => 'T_DIR',
        T_DIV_EQUAL => 'T_DIV_EQUAL',
        T_DNUMBER => 'T_DNUMBER',
        T_DOC_COMMENT => 'T_DOC_COMMENT',
        T_DO => 'T_DO',
        T_DOLLAR_OPEN_CURLY_BRACES => 'T_DOLLAR_OPEN_CURLY_BRACES',
        T_DOUBLE_ARROW => 'T_DOUBLE_ARROW',
        T_DOUBLE_CAST => 'T_DOUBLE_CAST',
        T_DOUBLE_COLON => 'T_DOUBLE_COLON',
        T_ECHO => 'T_ECHO',
        T_ELSE => 'T_ELSE',
        T_ELSEIF => 'T_ELSEIF',
        T_EMPTY => 'T_EMPTY',
        T_ENCAPSED_AND_WHITESPACE => 'T_ENCAPSED_AND_WHITESPACE',
        T_ENDDECLARE => 'T_ENDDECLARE',
        T_ENDFOR => 'T_ENDFOR',
        T_ENDFOREACH => 'T_ENDFOREACH',
        T_ENDIF => 'T_ENDIF',
        T_ENDSWITCH => 'T_ENDSWITCH',
        T_ENDWHILE => 'T_ENDWHILE',
        T_END_HEREDOC => 'T_END_HEREDOC',
        T_EVAL => 'T_EVAL',
        T_EXIT => 'T_EXIT',
        T_EXTENDS => 'T_EXTENDS',
        T_FILE => 'T_FILE',
        T_FINAL => 'T_FINAL',
        T_FOR => 'T_FOR',
        T_FOREACH => 'T_FOREACH',
        T_FUNCTION => 'T_FUNCTION',
        T_FUNC_C => 'T_FUNC_C',
        T_GLOBAL => 'T_GLOBAL',
        T_GOTO => 'T_GOTO',
        T_HALT_COMPILER => 'T_HALT_COMPILER',
        T_IF => 'T_IF',
        T_IMPLEMENTS => 'T_IMPLEMENTS',
        T_INC => 'T_INC',
        T_INCLUDE => 'T_INCLUDE',
        T_INCLUDE_ONCE => 'T_INCLUDE_ONCE',
        T_INLINE_HTML => 'T_INLINE_HTML',
        T_INSTANCEOF => 'T_INSTANCEOF',
        T_INT_CAST => 'T_INT_CAST',
        T_INTERFACE => 'T_INTERFACE',
        T_ISSET => 'T_ISSET',
        T_IS_EQUAL => 'T_IS_EQUAL',
        T_IS_GREATER_OR_EQUAL => 'T_IS_GREATER_OR_EQUAL',
        T_IS_IDENTICAL => 'T_IS_IDENTICAL',
        T_IS_NOT_EQUAL => 'T_IS_NOT_EQUAL',
        T_IS_NOT_IDENTICAL => 'T_IS_NOT_IDENTICAL',
        T_IS_SMALLER_OR_EQUAL => 'T_IS_SMALLER_OR_EQUAL',
        T_LINE => 'T_LINE',
        T_LIST => 'T_LIST',
        T_LNUMBER => 'T_LNUMBER',
        T_LOGICAL_AND => 'T_LOGICAL_AND',
        T_LOGICAL_OR => 'T_LOGICAL_OR',
        T_LOGICAL_XOR => 'T_LOGICAL_XOR',
        T_METHOD_C => 'T_METHOD_C',
        T_MINUS_EQUAL => 'T_MINUS_EQUAL',
        T_ML_COMMENT => 'T_ML_COMMENT',
        T_MOD_EQUAL => 'T_MOD_EQUAL',
        T_MUL_EQUAL => 'T_MUL_EQUAL',
        T_NS_C => 'T_NS_C',
        T_NEW => 'T_NEW',
        T_NUM_STRING => 'T_NUM_STRING',
        T_OBJECT_CAST => 'T_OBJECT_CAST',
        T_OBJECT_OPERATOR => 'T_OBJECT_OPERATOR',
        T_OLD_FUNCTION => 'T_OLD_FUNCTION',
        T_OPEN_TAG => 'T_OPEN_TAG',
        T_OPEN_TAG_WITH_ECHO => 'T_OPEN_TAG_WITH_ECHO',
        T_OR_EQUAL => 'T_OR_EQUAL',
        T_PAAMAYIM_NEKUDOTAYIM => 'T_PAAMAYIM_NEKUDOTAYIM',
        T_PLUS_EQUAL => 'T_PLUS_EQUAL',
        T_PRINT => 'T_PRINT',
        T_PRIVATE => 'T_PRIVATE',
        T_PUBLIC => 'T_PUBLIC',
        T_PROTECTED => 'T_PROTECTED',
        T_REQUIRE => 'T_REQUIRE',
        T_REQUIRE_ONCE => 'T_REQUIRE_ONCE',
        T_RETURN => 'T_RETURN',
        T_SL => 'T_SL',
        T_SL_EQUAL => 'T_SL_EQUAL',
        T_SR => 'T_SR',
        T_SR_EQUAL => 'T_SR_EQUAL',
        T_START_HEREDOC => 'T_START_HEREDOC',
        T_STATIC => 'T_STATIC',
        T_STRING => 'T_STRING',
        T_STRING_CAST => 'T_STRING_CAST',
        T_STRING_VARNAME => 'T_STRING_VARNAME',
        T_SWITCH => 'T_SWITCH',
        T_THROW => 'T_THROW',
        T_TRY => 'T_TRY',
        T_UNSET => 'T_UNSET',
        T_UNSET_CAST => 'T_UNSET_CAST',
        T_USE => 'T_USE',
        T_VAR => 'T_VAR',
        T_VARIABLE => 'T_VARIABLE',
        T_WHILE => 'T_WHILE',
        T_WHITESPACE => 'T_WHITESPACE',
        T_XOR_EQUAL => 'T_XOR_EQUAL'
    );

    /**
     * @param string $webappName
     */
    public function setWebappName($webappName)
    {
        $this->webappName = $webappName;
    }

    /**
     * @return string
     */
    public function getWebappName()
    {
        return $this->webappName;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param $projects
     */
    public function setProjects(array $projects)
    {
        $this->projects = $projects;
    }

    /**
     * @return array
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}