<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Schemaorg.recipe
 *
 * @copyright   (C) 2023 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\Schemaorg\Faqpage\Extension;

use Joomla\CMS\Event\Plugin\System\Schemaorg\BeforeCompileHeadEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Schemaorg\SchemaorgPluginTrait;
use Joomla\CMS\Schemaorg\SchemaorgPrepareDateTrait;
use Joomla\CMS\Schemaorg\SchemaorgPrepareDurationTrait;
use Joomla\Event\Priority;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Language\Text;
//use Joomla\CMS\Factory;


// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Schemaorg Plugin
 *
 * @since  5.0.0
 */

 
final class Faqpage extends CMSPlugin implements SubscriberInterface
{

 use SchemaorgPluginTrait;
 use SchemaorgPrepareDateTrait;

    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  5.0.0
     */
    protected $autoloadLanguage = true;

    /**
     * The name of the schema form
     *
     * @var   string
     * @since 5.0.0
     */
    protected $pluginName = 'FAQPage';
    
      /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onSchemaPrepareForm'       => 'onSchemaPrepareForm',
            'onSchemaBeforeCompileHead' => ['onSchemaBeforeCompileHead', Priority::BELOW_NORMAL],
        ];
    }
    
    public function onSchemaBeforeCompileHead(BeforeCompileHeadEvent $event): void
    {
        $schema = $event->getSchema();

        $graph = $schema->get('@graph');
        
        foreach ($graph as &$entry) {
            if (!isset($entry['@type']) || $entry['@type'] !== 'FAQPage')
            {
                continue;
            }
            
            //Factory::getApplication()->enqueueMessage("test".$entry['testvar']);
		// We are rendering the Q/A in the HTML document here
		$faqEntities = array();
 		foreach ($entry['mainEntity'] as $q)
 		{
           		$faqEntities[] = [
                "@type" => "Question",
                "name" => $q['name'] ?? '',
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $q['acceptedAnswer']
                ]
            ];
        }
		$entry['mainEntity'] = $faqEntities;
		}

    	$schema->set('@graph', $graph);
	}

}
