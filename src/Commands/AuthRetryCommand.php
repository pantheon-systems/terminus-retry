<?php
/**
 * This simple command shows the basics of how to add a new top-level command to Terminus.
 *
 * To add a command simply define a class as a subclass of `Pantheon\Terminus\Commands\TerminusCommand` and place it in
 * a php file inside the 'Commands' directory inside your plugin directory. The file and command class should end with
 * 'Command' in order to be found by Terminus.
 *
 * To specify what the command name should be use the `@command` tag in the actual command function DocBlock.
 *
 * This command can be invoked by running `terminus hello`
 */

namespace Pantheon\TerminusRetry\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Consolidation\AnnotatedCommand\CommandData;

/**
 * Retry command
 */
class AuthRetryCommand extends TerminusCommand
{
    /**
     * If remote:wp failed, rerun it once.
     *
     * @hook post-command remote:wp
     * @authorize
     */
    public function postRemoteWP($result, CommandData $commandData)
    {
        $this->rerun($result, $commandData, 'wp');
    }

    /**
     * If remote:drush failed, rerun it once.
     *
     * @hook post-command remote:drush
     * @authorize
     */
    public function postRemoteDrush($result, CommandData $commandData)
    {
        $this->rerun($result, $commandData, 'drush');
    }

    protected function rerun($result, CommandData $commandData, $cli)
    {
        $args    = $commandData->getArgsAndOptions();
        $options = $args['options'];
        $failed  = ! is_null($result) && is_a($result, 'Consolidation\AnnotatedCommand\CommandError');
        $retried = intval($this->getConfig()->get('retried')) ?: 0;
        $limit   = intval($this->getConfig()->get('retryLimit')) ?: 0;

        if ($failed && $retried < $limit) {
            $retried++;
            $site_env = $args['site_env'];

            // Make sure we have a define option
            $options['define'] = $options['define'] ?: [];

            // Remove an existing `retried=N`, since we'll add our own.
            foreach ($options['define'] as $idx => $val) {
                if (stristr($val, 'retried=')) {
                    unset($options['define'][ $idx ]);
                }
            }

            // Add a `retried=N` define.
            $options['define'][] = "retried=$retried";

            $optstr  = $this->processOptions($options);
            $subcmd  = implode(' ', $args['wp_command']);
            $cmd     = "terminus remote:$cli $site_env $optstr -- $subcmd";

            $this->log()->notice('Retrying failed command: ' . $cmd);
            exec($cmd);
        }
    }

    /**
     * Take the array of options and process them into an option string.
     *
     * @param array $options Symfony options array.
     * @return string        Option string.
     */
    public function processOptions($options)
    {
        $optstr = '';

        foreach ($options as $option => $vals) {
            if (false === $vals) {
                continue;
            } elseif (true === $vals) {
                $optstr .= " --$option";
            } else {
                foreach ($vals as $val) {
                    $optstr .= " --$option=\"$val\"";
                }
            }
        }

        return $optstr;
    }
}
