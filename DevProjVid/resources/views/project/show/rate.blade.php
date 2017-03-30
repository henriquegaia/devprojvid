<!-- ---------------------------------------------------------------------------
PROJECT
---------------------------------------------------------------------------- -->

<div class="row">
    <div class="col-xs-4">
        <table class="table table-bordered">
            <tr>
                <td>
                    <span 
                        class="glyphicon glyphicon-eye-open">
                    </span>
                </td>
                <td>
                    {{ $project->visits }}
                </td>
            </tr>
            <tr>
                <td>
                    @if (Auth::check() && $user->id != Auth::user()->id) 
                    <form 
                        action="{{ url('project/rate/' . $project->id . '/' . Auth::user()->id .'/up') }}" 
                        method="POST">
                        {{ csrf_field() }}
                        <button type="submit">
                            <span 
                                class="glyphicon glyphicon-thumbs-up"
                                id="rateProjectUp"    
                                >
                            </span>
                        </button>
                    </form>
                    @else
                    <span class="glyphicon glyphicon-thumbs-up">
                    </span>
                    @endif

                </td>

                <td>
                    <div id="rateProjectUpCount">
                        <?php echo $project->rates_up; ?>
                    </div>
                </td>

                @if($rate == 'rated_up')
                <td class="td-user-rate">
                    My vote
                </td>
                @endif
            </tr>
            <tr>
                <td>
                    @if (Auth::check() && $user->id != Auth::user()->id) 

                    <form 
                        action="{{ url('project/rate/' . $project->id . '/' . Auth::user()->id .'/down') }}" 
                        method="POST">
                        {{ csrf_field() }}
                        <button type="submit">
                            <span 
                                class="glyphicon glyphicon-thumbs-down"
                                id="rateProjectDown"    
                                >
                            </span>
                        </button>
                    </form>
                    @else
                    <span class="glyphicon glyphicon-thumbs-down">
                    </span>
                    @endif
                </td>

                <td>
                    <div id="rateProjectDownCount">
                        <?php echo $project->rates_down; ?>
                    </div>
                </td>

                @if($rate == 'rated_down')
                <td class="td-user-rate">
                    My vote
                </td>
                @endif

            </tr>
        </table>
    </div>
</div>
