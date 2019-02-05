<?php

/* test.twig.html */
class __TwigTemplate_443999a8aee4cbcce00e2d2edaa9c9655f5615cdcb6e9c1b24513d00325fb0da extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<h2>";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</h2>
";
    }

    public function getTemplateName()
    {
        return "test.twig.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "test.twig.html", "C:\\projects\\com\\tinker-framework\\storage\\views\\test.twig.html");
    }
}
